<?php

namespace Omnipay\Paysafecard\Test;

use Omnipay\Paysafecard\Gateway;
use Omnipay\Paysafecard\Message\Request\AuthorizeRequest;
use Omnipay\Paysafecard\Message\Request\FetchTransactionRequest;
use Omnipay\Paysafecard\Message\Request\PurchaseRequest;
use Omnipay\Paysafecard\Message\Request\ValidateRefundRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway();
    }

    public function testGetName(): void
    {
        $this->assertEquals('Paysafecard', $this->gateway->getName());
    }

    public function testApiKeyIsSet(): void
    {
        $this->gateway->setApiKey('abc');

        $this->assertEquals('abc', $this->gateway->getApiKey());
    }

    public function testFetchTransaction(): void
    {
        $this->assertInstanceOf(FetchTransactionRequest::class, $this->gateway->fetchTransaction());
    }

    public function testValidateRefund(): void
    {
        $this->assertInstanceOf(ValidateRefundRequest::class, $this->gateway->validateRefund());
    }
}
