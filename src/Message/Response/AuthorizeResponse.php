<?php

namespace Omnipay\Paysafecard\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Paysafecard\Exception\RedirectUrlException;

class AuthorizeResponse extends PaysafecardResponse implements RedirectResponseInterface
{
    public function isRedirect(): bool
    {
        try {
            $this->getRedirectUrl();

            return true;
        } catch (RedirectUrlException $exception) {
            return false;
        }
    }

    public function getRedirectUrl(): string
    {
        if (empty($this->data['redirect']['auth_url'])) {
            throw new RedirectUrlException('No auth url available');
        }

        return $this->data['redirect']['auth_url'];
    }
}
