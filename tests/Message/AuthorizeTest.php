<?php


namespace Omnipay\Paysafecard\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Paysafecard\Message\Request\AuthorizeRequest;
use Omnipay\Paysafecard\Message\Request\PaysafecardRequest;
use Omnipay\Paysafecard\Message\Response\AuthorizeResponse;
use Omnipay\Tests\TestCase;

class AuthorizeTest extends TestCase
{
    /**
     * @var AuthorizeRequest
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'currency' => 'EUR',
            'amount' => 10.00,
            'success_url' => 'https://success',
            'failure_url' => 'https://failure',
            'notification_url' => 'https://notification',
            'customer_id' => '1234',
        ]);
    }

    public function testGetAllIndividualData(): void
    {
        $this->assertSame('10.00', $this->request->getAmount());
        $this->assertSame('EUR', $this->request->getCurrency());
        $this->assertSame('https://success', $this->request->getSuccessUrl());
        $this->assertSame('https://failure', $this->request->getFailureUrl());
        $this->assertSame('https://notification', $this->request->getNotificationUrl());
        $this->assertSame('1234', $this->request->getCustomerId());
    }

    public function testSetAndGetAllIndividualData(): void
    {
        $this->request->setAmount('11.00');
        $this->request->setCurrency('USD');
        $this->request->setSuccessUrl('https://url/success');
        $this->request->setFailureUrl('https://url/failure');
        $this->request->setNotificationUrl('https://url/notification');
        $this->request->setCustomerId('1000');

        $this->assertSame('11.00', $this->request->getAmount());
        $this->assertSame('USD', $this->request->getCurrency());
        $this->assertSame('https://url/success', $this->request->getSuccessUrl());
        $this->assertSame('https://url/failure', $this->request->getFailureUrl());
        $this->assertSame('https://url/notification', $this->request->getNotificationUrl());
        $this->assertSame('1000', $this->request->getCustomerId());
    }

    public function testGetData(): void
    {
        $data = [
            'type' => 'PAYSAFECARD',
            'amount' => 10.00,
            'currency' => 'EUR',
            'redirect' => [
                'success_url' => 'https://success',
                'failure_url' => 'https://failure',
            ],
            'notification_url' => 'https://notification',
            'customer' => [
                'id' => '1234',
            ],
        ];

        $this->assertSame($data, $this->request->getData());
    }

    public function testSendDataWithSuccess(): void
    {
        $apiKey = 'abc';
        $this->request->setApiKey($apiKey);
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->request->send();

        $lastRequest = $this->getMockClient()->getLastRequest();

        $expectedRequest = new Request(
            PaysafecardRequest::POST,
            'https://api.paysafecard.com/v1/payments',
            [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Basic ' . base64_encode($apiKey),
            ],
            json_encode([
                'type' => 'PAYSAFECARD',
                'amount' => 10.00,
                'currency' => 'EUR',
                'redirect' => [
                    'success_url' => 'https://success',
                    'failure_url' => 'https://failure',
                ],
                'notification_url' => 'https://notification',
                'customer' => [
                    'id' => '1234',
                ],
            ])
        );

        $this->assertEquals($expectedRequest->getMethod(), $lastRequest->getMethod());

        $this->assertEquals($expectedRequest->getUri(), $lastRequest->getUri());

        $this->assertJsonStringEqualsJsonString(
            (string) $expectedRequest->getBody(),
            (string) $lastRequest->getBody()
        );

        $this->assertInstanceOf(AuthorizeResponse::class, $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR', $response->getPaymentId());
        $this->assertEquals('INITIATED', $response->getStatus());
        $this->assertEquals('PAYMENT', $response->getObject());
        $this->assertEquals('https://dv.customer.paysafecard.com/psccustomer/GetCustomerPanelServlet?mid=1000000007&mtid=pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR&amount=10.00&currency=EUR', $response->getRedirectUrl());
    }

    public function testSendDataWithAuthorizationFailure(): void
    {
        $this->request->setApiKey('abc');
        $this->setMockHttpResponse('AuthorizeFailure.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->request->send();

        $this->assertInstanceOf(AuthorizeResponse::class, $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"code":"invalid_api_key","message":"Authentication failed","number":10008}', $response->getMessage());

        try {
            $response->getRedirectUrl();
        } catch (\RuntimeException $exception) {
            $this->assertEquals('No auth url available', $exception->getMessage());
        }
    }
}
