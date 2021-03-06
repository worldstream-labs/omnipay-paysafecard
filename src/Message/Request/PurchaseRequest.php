<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\PurchaseResponse;
use Psr\Http\Message\ResponseInterface as HttpResponse;

class PurchaseRequest extends AuthorizeRequest
{
    protected function getPaysafecardResponse(HttpResponse $response): ResponseInterface
    {
        return new PurchaseResponse($this, $response);
    }
}
