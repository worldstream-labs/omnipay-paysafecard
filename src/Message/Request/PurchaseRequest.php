<?php


namespace Omnipay\Paysafecard\Message\Request;


use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paysafecard\Message\Response\PurchaseResponse;

class PurchaseRequest extends AbstractPaysafecardRequest
{
    /*
        {
          "type": "PAYSAFECARD",
          "amount": 0.01,
          "currency": "EUR",
          "redirect": {
            "success_url": "https://notification.com/{payment_id}",
            "failure_url": "https://notification.com/{payment_id}"
          },
          "notification_url": "https://notification.com/{payment_id}",
          "customer": {
            "id": "merchantclientid5HzDvoZSodKDJ7X7VQKrtestAutomation",
            "min_age": "18",
            "kyc_level": "SIMPLE",
            "country_restriction": "AT"
          },
          "submerchant_id": "1",
          "shop_id": "shop1"
        }
     */
    public function getData()
    {
        $data = [];
        $data['type'] = self::TYPE_PAYSAFECARD;
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        return $data;
    }


    /**
     * @param array $data
     * @return ResponseInterface|PurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/payments', $data);

        return $this->response = new PurchaseResponse($this, $response);
    }
}
