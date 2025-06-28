<?php

namespace lakpasherpa\NepaliPaymentGateways\Controllers;

use Illuminate\Http\Request;
use lakpasherpa\NepaliPaymentGateways\Services\Esewa;

class EsewaController extends BasePaymentController
{
  protected function getPaymentService()
  {
    return app('esewa');
  }

  protected function getPaymentMethod(): string
  {
    return 'esewa';
  }

  protected function processInquiry(Request $request, $paymentService)
  {
    $decodedString = base64_decode($request->data);
    $data = json_decode($decodedString, true);

    return $paymentService->inquiry($data['transaction_uuid'] ?? '', $data);
  }
}
