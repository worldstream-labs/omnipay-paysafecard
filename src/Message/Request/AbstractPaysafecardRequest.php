<?php


namespace Omnipay\Paysafecard\Message\Request;


use Omnipay\Common\Message\AbstractRequest;
use function base64_encode;

abstract class AbstractPaysafecardRequest extends AbstractRequest
{
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';

    const TYPE_PAYSAFECARD = 'PAYSAFECARD';

    protected $apiVersion = 'v1';
    protected $baseUrl = 'https://api.paysafecard.com/';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @inheritDoc
     */
    public function sendRequest($method, $endpoint, array $data = null)
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($this->getApiKey()),
        ];

        $response = $this->httpClient->request(
            $method,
            $this->baseUrl . $this->apiVersion . $endpoint,
            $headers,
            ($data === null || $data === []) ? null : json_encode($data)
        );

        return json_decode($response->getBody(), true);
    }
}
