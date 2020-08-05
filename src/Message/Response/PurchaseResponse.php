<?php

namespace Omnipay\Paysafecard\Message\Response;

class PurchaseResponse extends AuthorizeResponse
{
    /**
     * Cannot be successful because the user needs to redirect to `Paysafecard`
     */
    public function isSuccessful(): bool
    {
        if ($this->isRedirect()) {
            return false;
        }

        return parent::isSuccessful();
    }
}
