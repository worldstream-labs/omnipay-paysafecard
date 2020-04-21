<?php


namespace Omnipay\Paysafecard\Test\Message;


use Omnipay\Paysafecard\Message\Request\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    protected $request;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            "type" => "PAYSAFECARD",
            "amount" => 0.01,
            "currency" => "EUR",
            "redirect" => [
                "success_url" => "https://notification.com/123",
                "failure_url" => "https://notification.com/123",
            ],
            "notification_url" => "https://notification.com/123",
            "customer" => [
                "id" => "merchantclientid5HzDvoZSodKDJ7X7VQKrtestAutomation",
                "min_age" => "18",
                "kyc_level" => "SIMPLE",
                "country_restriction" => "AT",
            ],
            "submerchant_id" => "1",
            "shop_id" => "shop1",
        ]);
    }

    public function testGetData()
    {
        $this->request->initialize([
            "type" => "PAYSAFECARD",
            "amount" => 0.01,
            "currency" => "EUR",
            "redirect" => [
                "success_url" => "https://notification.com/123",
                "failure_url" => "https://notification.com/123",
            ],
            "notification_url" => "https://notification.com/123",
            "customer" => [
                "id" => "merchantclientid5HzDvoZSodKDJ7X7VQKrtestAutomation",
                "min_age" => "18",
                "kyc_level" => "SIMPLE",
                "country_restriction" => "AT",
            ],
            "submerchant_id" => "1",
            "shop_id" => "shop1",
        ]);

        $data = $this->request->getData();

        $this->assertSame("0.01", $data['amount']);
    }
}
