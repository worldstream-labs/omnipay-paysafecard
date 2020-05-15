<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\ValidateRefundResponse;
use Psr\Http\Message\ResponseInterface as HttpResponse;

class ValidateRefundRequest extends RefundRequest
{
    public function getData(): array
    {
        $data = parent::getData();
        $data['capture'] = false;

        return $data;
    }

    protected function getEndpoint(): string
    {
        return sprintf('/payments/%s/refunds', $this->getPaymentId());
    }

    protected function getPaysafecardResponse(HttpResponse $response): ResponseInterface
    {
        return new ValidateRefundResponse($this, $response);
    }
}
