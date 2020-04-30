<?php

namespace Omnipay\Paysafecard\Message\Response;

class FetchTransactionResponse extends PaysafecardResponse
{
    public function isRedirect(): bool
    {
        return !empty($this->data['redirect']['auth_url']);
    }

    public function getRedirectUrl(): ?string
    {
        if (!$this->isRedirect()) {
            return null;
        }

        return $this->data['redirect']['auth_url'];
    }
}
