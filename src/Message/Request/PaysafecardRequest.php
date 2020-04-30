<?php

namespace Omnipay\Paysafecard\Message\Request;

use Omnipay\Common\Message\AbstractRequest;
use Psr\Http\Message\ResponseInterface;
use function base64_encode;
use function json_encode;

abstract class PaysafecardRequest extends AbstractRequest
{
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';

    const TYPE_PAYSAFECARD = 'PAYSAFECARD';

    const ENDPOINT_LIVE = 'https://api.paysafecard.com/';
    const ENDPOINT_TEST = 'https://apitest.paysafecard.com/';

    const API_VERSION = 'v1';

    public function getApiKey(): string
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value): self
    {
        return $this->setParameter('apiKey', $value);
    }

    private function getUrl(string $endpoint): string
    {
        $url = $this->getTestMode() ? self::ENDPOINT_TEST : self::ENDPOINT_LIVE;

        return sprintf('%s%s%s', $url, self::API_VERSION, $endpoint);
    }

    public function sendRequest(string $method, string $endpoint, array $data = []): ResponseInterface
    {
        $headers = [
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => sprintf('Basic %s', base64_encode($this->getApiKey())),
        ];

        return $this->httpClient->request(
            $method,
            $this->getUrl($endpoint),
            $headers,
            empty($data) ? null : json_encode($data)
        );
    }
}
