<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\AuthorizeResponse;
use Psr\Http\Message\ResponseInterface as HttpResponse;

class AuthorizeRequest extends PaysafecardRequest
{
    public function getData(): array
    {
        $data = [
            'type' => self::TYPE_PAYSAFECARD,
            'amount' => (float) $this->getAmount(),
            'currency' => $this->getCurrency(),
            'redirect' => [
                'success_url' => $this->getSuccessUrl(),
                'failure_url' => $this->getFailureUrl(),
            ],
            'notification_url' => $this->getNotificationUrl(),
        ];

        if (!empty($this->getCustomerId())) {
            $data['customer'] = [
                'id' => $this->getCustomerId(),
            ];
        }

        return $data;
    }

    public function getSuccessUrl(): string
    {
        return $this->getParameter('successUrl');
    }

    public function setSuccessUrl(string $value): self
    {
        return $this->setParameter('successUrl', $value);
    }

    public function getFailureUrl(): string
    {
        return $this->getParameter('failureUrl');
    }

    public function setFailureUrl(string $value): self
    {
        return $this->setParameter('failureUrl', $value);
    }

    public function getNotificationUrl(): string
    {
        return $this->getParameter('notificationUrl');
    }

    public function setNotificationUrl(string $value): self
    {
        return $this->setParameter('notificationUrl', $value);
    }

    public function getCustomerId(): ?string
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId(string $value): self
    {
        return $this->setParameter('customerId', $value);
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(self::POST, '/payments', $data);

        $this->response = $this->getPaysafecardResponse($response);

        return $this->response;
    }

    protected function getPaysafecardResponse(HttpResponse $response): ResponseInterface
    {
        return new AuthorizeResponse($this, $response);
    }
}
