<?php

namespace lakpasherpa\NepaliPaymentGateways\Contracts;

interface PaymentGatewayInterface
{
  public function pay(float $amount, string $return_url, string $purchase_order_id, string $purchase_order_name, array $customer_info = []);

  public function inquiry(string $transaction_id, array $data = []);

  public function isSuccess(array $response): bool;

  public function getAmount(array $response): float;
}
