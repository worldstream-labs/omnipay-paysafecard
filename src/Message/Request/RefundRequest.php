<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\RefundResponse;
use Psr\Http\Message\ResponseInterface as HttpResponse;

class RefundRequest extends PaysafecardRequest
{
    public function getPaymentId(): string
    {
        return $this->getParameter('paymentId');
    }

    public function setPaymentId(string $value): self
    {
        return $this->setParameter('paymentId', $value);
    }

    public function getRefundId(): ?string
    {
        return $this->getParameter('refundId');
    }

    public function setRefundId(string $value): self
    {
        return $this->setParameter('refundId', $value);
    }

    public function getCustomerId(): ?string
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId(string $value): self
    {
        return $this->setParameter('customerId', $value);
    }

    public function getCustomerEmail(): ?string
    {
        return $this->getParameter('customerEmail');
    }

    public function setCustomerEmail(string $value): self
    {
        return $this->setParameter('customerEmail', $value);
    }

    public function getData(): array
    {
        if (!empty($this->getRefundId())) {
            return [];
        }

        $data = [
            'type' => self::TYPE_PAYSAFECARD,
            'capture' => true,
            'amount' => (float) $this->getAmount(),
            'currency' => $this->getCurrency(),
        ];

        if (!empty($this->getCustomerEmail())) {
            $data['customer']['email'] = $this->getCustomerEmail();
        }

        if (!empty($this->getCustomerId())) {
            $data['customer']['id'] = $this->getCustomerId();
        }

        return $data;
    }

    protected function getEndpoint(): string
    {
        if ($this->getRefundId()) {
            return sprintf('/payments/%s/refunds/%s/capture', $this->getPaymentId(), $this->getRefundId());
        }

        return sprintf('/payments/%s/refunds', $this->getPaymentId());
    }

    protected function getPaysafecardResponse(HttpResponse $response): ResponseInterface
    {
        return new RefundResponse($this, $response);
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            self::POST,
            $this->getEndpoint(),
            $data
        );

        $this->response = $this->getPaysafecardResponse($response);

        return $this->response;
    }
}
