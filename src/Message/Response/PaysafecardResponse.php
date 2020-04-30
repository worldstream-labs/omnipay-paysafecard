<?php

namespace Omnipay\Paysafecard\Message\Response;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PaysafecardResponse extends AbstractResponse
{
    /**
     * @var int
     */
    private $statusCode;

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->statusCode = $response->getStatusCode();

        parent::__construct($request, json_decode($response->getBody(), true));
    }

    public function getPaymentId(): string
    {
        return $this->data['id'];
    }

    public function isSuccessful(): bool
    {
        return $this->statusCode < 400;
    }

    public function getStatus(): string
    {
        return $this->data['status'];
    }

    public function getObject(): string
    {
        return $this->data['object'];
    }

    public function getMessage(): string
    {
        return json_encode($this->data);
    }
}
