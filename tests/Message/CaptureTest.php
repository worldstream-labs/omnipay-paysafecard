<?php

namespace Omnipay\Paysafecard\Test\Message;

use Omnipay\Paysafecard\Message\Request\CaptureRequest;
use Omnipay\Paysafecard\Message\Response\CaptureResponse;
use Omnipay\Tests\TestCase;

class CaptureTest extends TestCase
{
    private const PAYMENT_ID = 'pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR';
    /**
     * @var CaptureRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setPaymentId(self::PAYMENT_ID);
    }

    public function testGetDataIsEmpty(): void
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
        $this->setMockHttpResponse('CaptureSuccess.txt');

        /** @var CaptureResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(CaptureResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('SUCCESS', $response->getStatus());
        $this->assertEquals('PAYMENT', $response->getObject());
    }

    public function testSendDataWithAuthenticationFailure(): void
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('CaptureFailure.txt');

        /** @var CaptureResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(CaptureResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"code":"invalid_api_key","message":"Authentication failed","number":10008}', $response->getMessage());
    }
}
