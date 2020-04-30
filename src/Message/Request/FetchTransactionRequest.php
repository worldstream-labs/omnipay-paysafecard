<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\FetchTransactionResponse;

class FetchTransactionRequest extends PaysafecardRequest
{
    public function getPaymentId(): string
    {
        return $this->getParameter('paymentId');
    }

    public function setPaymentId($value): self
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
            self::GET,
            sprintf('/payments/%s', $this->getPaymentId()),
            $data
        );

        $this->response = new FetchTransactionResponse($this, $response);

        return $this->response;
    }
}
