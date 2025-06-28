<?php

namespace lakpsherpa\NepaliPaymentGateways\Services;

class Esewa extends BasePayment
{
  protected function getConfig(): array
  {
    return config('nepali-payment.esewa');
  }

  public function pay(float $amount, string $return_url, string $purchase_order_id, string $purchase_order_name, array $customer_info = [])
  {
    $transaction_uuid = $purchase_order_id . '_' . uniqid();

    $data = [
      'amount' => $amount,
      'tax_amount' => 0,
      'total_amount' => $amount,
      'transaction_uuid' => $transaction_uuid,
      'product_code' => $this->config['merchant_code'],
      'product_service_charge' => 0,
      'product_delivery_charge' => 0,
      'success_url' => $return_url,
      'failure_url' => $return_url,
      'signed_field_names' => 'total_amount,transaction_uuid,product_code',
    ];

    $data['signature'] = $this->generateSignature([
      $data['total_amount'],
      $data['transaction_uuid'],
      $data['product_code']
    ], $this->config['secret_key']);

    return view('nepal-payment::esewa.form', [
      'data' => $data,
      'process_url' => $this->config['base_url'] . 'main/v2/form'
    ]);
  }

  public function inquiry(string $transaction_uuid, array $data = [])
  {
    $url = $this->config['base_url'] . 'transaction/status/';

    $requestData = [
      'product_code' => $this->config['merchant_code'],
      'transaction_uuid' => $transaction_uuid,
      'total_amount' => $data['total_amount'] ?? 0,
    ];

    return $this->makeRequest('POST', $url, [
      'form_params' => $requestData
    ]);
  }

  public function isSuccess(array $response): bool
  {
    return isset($response['status']) && strtolower($response['status']) === 'complete';
  }

  public function getAmount(array $response): float
  {
    return (float) ($response['total_amount'] ?? 0);
  }
}
