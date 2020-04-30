<?php

namespace Omnipay\Paysafecard\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class AuthorizeResponse extends PaysafecardResponse implements RedirectResponseInterface
{
    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl(): string
    {
        if (empty($this->data['redirect']['auth_url'])) {
            throw new \RuntimeException('No auth url available');
        }

        return $this->data['redirect']['auth_url'];
    }
}
