<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\CaptureResponse;
use Psr\Http\Message\ResponseInterface as HttpResponse;

class CaptureRequest extends PaysafecardRequest
{
    public function getPaymentId(): string
    {
        return $this->getParameter('paymentId');
    }

    public function setPaymentId(string $value): self
    {
        return $this->setParameter('paymentId', $value);
    }

    public function getData(): array
    {
        return [];
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            self::POST,
            sprintf('/payments/%s/capture', $this->getPaymentId()),
            $data
        );

        $this->response = $this->getPaysafecardResponse($response);

        return $this->response;
    }

    protected function getPaysafecardResponse(HttpResponse $response): ResponseInterface
    {
        return new CaptureResponse($this, $response);
    }
}
