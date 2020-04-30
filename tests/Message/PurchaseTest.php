<?php

namespace Omnipay\Paysafecard\Test\Message;

use Omnipay\Paysafecard\Message\Request\PurchaseRequest;
use Omnipay\Paysafecard\Message\Response\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseTest extends TestCase
{
    public function testSendDataWithSuccess()
    {
        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'currency' => 'EUR',
            'amount' => 10.00,
            'success_url' => 'https://success',
            'failure_url' => 'https://failure',
            'notification_url' => 'https://notification',
            'customer_id' => '1234',
        ]);

        $apiKey = 'abc';
        $request->setApiKey($apiKey);
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        /** @var PurchaseResponse $response */
        $response = $request->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
    }
}
