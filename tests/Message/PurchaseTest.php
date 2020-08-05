<?php

namespace Omnipay\Paysafecard\Test\Message;

use Omnipay\Paysafecard\Message\Request\PurchaseRequest;
use Omnipay\Paysafecard\Message\Response\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'currency'         => 'EUR',
            'amount'           => 10.00,
            'success_url'      => 'https://success',
            'failure_url'      => 'https://failure',
            'notification_url' => 'https://notification',
            'customer_id'      => '1234',
            'apiKey'           => 'abc',
        ]);
    }

    public function testSendDataWithSuccess(): void
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
    }

    public function testSendDataWithFailure(): void
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');

        /** @var PurchaseResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(PurchaseResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
    }
}
