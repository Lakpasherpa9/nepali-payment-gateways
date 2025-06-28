<?php

namespace lakpasherpa\NepaliPaymentGateways\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use lakpasherpa\NepaliPaymentGateways\Contracts\PaymentGatewayInterface;

abstract class BasePayment implements PaymentGatewayInterface
{
  protected $client;
  protected $config;

  public function __construct()
  {
    $this->client = new Client(['timeout' => config('nepal-payment.timeout', 30)]);
    $this->config = $this->getConfig();
  }

  abstract protected function getConfig(): array;

  protected function makeRequest(string $method, string $url, array $options = [])
  {
    try {
      $response = $this->client->request($method, $url, $options);
      return json_decode($response->getBody()->getContents(), true);
    } catch (RequestException $e) {
      throw new \Exception('Payment gateway request failed: ' . $e->getMessage());
    }
  }

  protected function generateSignature(array $data, string $secret_key): string
  {
    $message = implode(',', $data);
    return base64_encode(hash_hmac('sha256', $message, $secret_key, true));
  }
}
