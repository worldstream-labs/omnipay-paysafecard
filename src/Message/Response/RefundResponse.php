<?php

namespace Omnipay\Paysafecard\Message\Response;

class RefundResponse extends PaysafecardResponse
{
    public function getRefundId(): string
    {
        return $this->data['id'];
    }
}
