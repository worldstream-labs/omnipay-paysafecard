<?php

namespace Omnipay\Paysafecard\Test;

use Omnipay\Paysafecard\Gateway;
use Omnipay\Paysafecard\Message\Request\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway();
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => 0.01, 'currency' => 'EUR'));

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('0.01', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }
}
