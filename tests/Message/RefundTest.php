<?php

namespace Omnipay\Paysafecard\Test\Message;

use GuzzleHttp\Psr7\Request;
use Omnipay\Paysafecard\Message\Request\PaysafecardRequest;
use Omnipay\Paysafecard\Message\Request\RefundRequest;
use Omnipay\Paysafecard\Message\Response\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundTest extends TestCase
{
    private const PAYMENT_ID = 'pay_1000000007_Hukab77YIXzKUYMdgPDBQ986ihNUQChu_EUR';
    private const REFUND_ID = 'ref_1234';
    /**
     * @var RefundRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSetAndGetAllIndividualData(): void
    {
        $this->request->setAmount('10.00');
        $this->request->setCurrency('EUR');
        $this->request->setPaymentId(self::PAYMENT_ID);
        $this->request->setCustomerId('1000');
        $this->request->setCustomerEmail('test@mail.com');

        $this->assertSame('10.00', $this->request->getAmount());
        $this->assertSame('EUR', $this->request->getCurrency());
        $this->assertSame(self::PAYMENT_ID, $this->request->getPaymentId());
        $this->assertSame('1000', $this->request->getCustomerId());
        $this->assertSame('test@mail.com', $this->request->getCustomerEmail());
    }
    
    public function testGetDataWithoutRefundId(): void
    {
        $amount = 10.00;
        $currency = 'EUR';
        $customerId = '1000';
        $customerEmail = 'test@mail.com';

        $this->request->setAmount($amount);
        $this->request->setCurrency($currency);
        $this->request->setPaymentId(self::PAYMENT_ID);
        $this->request->setCustomerId($customerId);
        $this->request->setCustomerEmail($customerEmail);

        $expectedData = [
            'type' => 'PAYSAFECARD',
            'capture' => true,
            'amount' => $amount,
            'currency' => $currency,
            'customer' => [
                'email' => $customerEmail,
                'id' => $customerId,
            ],
        ];

        $this->assertSame($expectedData, $this->request->getData());
    }

    public function testGetDataWithRefundId(): void
    {
        $this->request->setAmount('10.00');
        $this->request->setCurrency('EUR');
        $this->request->setPaymentId(self::PAYMENT_ID);
        $this->request->setCustomerId('1000');
        $this->request->setCustomerEmail('test@mail.com');
        $this->request->setRefundId('ref_123');

        $this->assertSame([], $this->request->getData());
    }

    public function testSendDataWithSuccess(): void
    {
        $apiKey = 'apiKey';
        $this->request->setApiKey($apiKey);
        $this->request->setAmount('10.00');
        $this->request->setCurrency('EUR');
        $this->request->setPaymentId(self::PAYMENT_ID);
        $this->request->setCustomerId('1000');
        $this->request->setCustomerEmail('test@mail.com');

        $this->setMockHttpResponse('RefundSuccess.txt');

        /** @var RefundResponse $response */
        $response = $this->request->send();

        $lastRequest = $this->getMockClient()->getLastRequest();

        $expectedRequest = new Request(
            PaysafecardRequest::POST,
            sprintf('https://api.paysafecard.com/v1/payments/%s/refunds', self::PAYMENT_ID),
            [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Basic ' . base64_encode($apiKey),
            ],
            json_encode([
                'type' => 'PAYSAFECARD',
                'capture' => true,
                'amount' => 10.00,
                'currency' => 'EUR',
                'customer' => [
                    'email' => 'test@mail.com',
                    'id' => '1000',
                ],
            ])
        );

        $this->assertInstanceOf(RefundResponse::class, $response);

        $this->assertEquals($expectedRequest->getMethod(), $lastRequest->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $lastRequest->getUri());

        $this->assertJsonStringEqualsJsonString(
            (string) $expectedRequest->getBody(),
            (string) $lastRequest->getBody()
        );

        $this->assertEquals(self::REFUND_ID, $response->getRefundId());
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('SUCCESSFULEUpdated', $response->getStatus());
    }

    public function testSendDataIncludingRefundIdWithSuccess(): void
    {
        $apiKey = 'apiKey';
        $this->request->setApiKey($apiKey);
        $this->request->setRefundId(self::REFUND_ID);
        $this->request->setPaymentId(self::PAYMENT_ID);

        $this->setMockHttpResponse('RefundSuccess.txt');

        /** @var RefundResponse $response */
        $response = $this->request->send();

        $lastRequest = $this->getMockClient()->getLastRequest();

        $expectedUrl = sprintf(
            'https://api.paysafecard.com/v1/payments/%s/refunds/%s/capture',
            self::PAYMENT_ID,
            self::REFUND_ID
        );

        $this->assertInstanceOf(RefundResponse::class, $response);

        $this->assertEquals(PaysafecardRequest::POST, $lastRequest->getMethod());
        $this->assertEquals($expectedUrl, $lastRequest->getUri());
        $this->assertEmpty((string) $lastRequest->getBody());
        $this->assertEquals(self::REFUND_ID, $response->getRefundId());
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('SUCCESSFULEUpdated', $response->getStatus());
    }

    public function testSendDataWithExceedingAmountWillReturnError(): void
    {
        $apiKey = 'apiKey';
        $this->request->setApiKey($apiKey);
        $this->request->setAmount('20.00');
        $this->request->setCurrency('EUR');
        $this->request->setPaymentId(self::PAYMENT_ID);
        $this->request->setCustomerId('1000');
        $this->request->setCustomerEmail('test@mail.com');

        $this->setMockHttpResponse('RefundFailure.txt');

        $response = $this->request->send();

        $lastRequest = $this->getMockClient()->getLastRequest();

        $expectedRequest = new Request(
            PaysafecardRequest::POST,
            sprintf('https://api.paysafecard.com/v1/payments/%s/refunds', self::PAYMENT_ID),
            [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Basic ' . base64_encode($apiKey),
            ],
            json_encode([
                'type' => 'PAYSAFECARD',
                'capture' => true,
                'amount' => 20.00,
                'currency' => 'EUR',
                'customer' => [
                    'email' => 'test@mail.com',
                    'id' => '1000',
                ],
            ])
        );

        $this->assertInstanceOf(RefundResponse::class, $response);

        $this->assertEquals($expectedRequest->getMethod(), $lastRequest->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $lastRequest->getUri());

        $this->assertJsonStringEqualsJsonString(
            (string) $expectedRequest->getBody(),
            (string) $lastRequest->getBody()
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('{"code":"merchant_refund_exceeds_original_transaction","message":"Merchant refund exceeds original transaction","number":3179}', $response->getMessage());
    }
}
