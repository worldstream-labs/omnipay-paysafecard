<?php

namespace Omnipay\Paysafecard;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paysafecard\Message\Request\AuthorizeRequest;
use Omnipay\Paysafecard\Message\Request\CaptureRequest;
use Omnipay\Paysafecard\Message\Request\CompletePurchaseRequest;
use Omnipay\Paysafecard\Message\Request\FetchTransactionRequest;
use Omnipay\Paysafecard\Message\Request\PurchaseRequest;
use Omnipay\Paysafecard\Message\Request\RefundRequest;
use Omnipay\Paysafecard\Message\Request\ValidateRefundRequest;

/**
 * @method RequestInterface      completeAuthorize(array $options = [])
 * @method RequestInterface      void(array $options = [])
 * @method RequestInterface      createCard(array $options = [])
 * @method RequestInterface      updateCard(array $options = [])
 * @method RequestInterface      deleteCard(array $options = [])
 * @method NotificationInterface acceptNotification(array $options = array())
 */
class Gateway extends AbstractGateway
{
    const NAME = 'Paysafecard';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefaultParameters(): array
    {
        return [
            'apiKey' => '',
            'testMode' => true,
        ];
    }

    public function authorize(array $options = []): AuthorizeRequest
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function capture(array $options = []): CaptureRequest
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    public function purchase(array $options = []): PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = []): CompletePurchaseRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): FetchTransactionRequest
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    public function validateRefund(array $options = []): ValidateRefundRequest
    {
        return $this->createRequest(ValidateRefundRequest::class, $options);
    }

    public function refund(array $options = []): RefundRequest
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value): self
    {
        return $this->setParameter('apiKey', $value);
    }
}
