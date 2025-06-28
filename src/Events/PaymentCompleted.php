<?php

namespace lakpasherpa\NepaliPaymentGateways\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleted
{
  use Dispatchable, SerializesModels;

  public $paymentData;

  public function __construct(array $paymentData)
  {
    $this->paymentData = $paymentData;
  }
}
