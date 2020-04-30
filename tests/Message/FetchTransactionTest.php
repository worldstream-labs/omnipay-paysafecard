<?php

namespace Omnipay\Paysafecard\Test\Message;

use Omnipay\Paysafecard\Message\Request\FetchTransactionRequest;
use Omnipay\Paysafecard\Message\Response\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionTest extends TestCase
{
    private const PAYMENT_ID = 'pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR';
    /**
     * @var FetchTransactionRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setPaymentId(self::PAYMENT_ID);
    }

    public function testGetDataReturnsAnEmptyArray(): void
    {
        $this->assertEquals([], $this->request->getData());
    }

    public function testGetPaymentId(): void
    {
        $this->assertEquals(self::PAYMENT_ID, $this->request->getPaymentId());
    }

    public function testSendDataWithSuccess(): void
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        /** @var FetchTransactionResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('SUCCESS', $response->getStatus());
        $this->assertEquals('PAYMENT', $response->getObject());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals(
            'https://dv.customer.paysafecard.com/psccustomer/GetCustomerPanelServlet?mid=1000000007&mtid=pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR&amount=10.00&currency=EUR',
            $response->getRedirectUrl()
        );
    }

    public function testSendDataWithAuthorizationFailure(): void
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('FetchTransactionFailure.txt');

        /** @var FetchTransactionResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(FetchTransactionResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getRedirectUrl());
    }
}
