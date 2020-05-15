<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\CompletePurchaseResponse;
use Psr\Http\Message\ResponseInterface as HttpResponse;

class CompletePurchaseRequest extends CaptureRequest
{
    protected function getPaysafecardResponse(HttpResponse $response): ResponseInterface
    {
        return new CompletePurchaseResponse($this, $response);
    }
}
