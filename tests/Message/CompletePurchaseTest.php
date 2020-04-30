<?php

namespace Omnipay\Paysafecard\Test\Message;

use Omnipay\Paysafecard\Message\Request\CompletePurchaseRequest;
use Omnipay\Paysafecard\Message\Response\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseTest extends TestCase
{
    private const PAYMENT_ID = 'pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR';

    public function testSendDataWithSuccess()
    {
        $request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->setPaymentId(self::PAYMENT_ID);

        $apiKey = 'abc';
        $request->setApiKey($apiKey);
        $this->setMockHttpResponse('CaptureSuccess.txt');

        /** @var CompletePurchaseResponse $response */
        $response = $request->send();

        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('SUCCESS', $response->getStatus());
        $this->assertEquals('PAYMENT', $response->getObject());
    }
}
