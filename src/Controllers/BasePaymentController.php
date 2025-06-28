<?php

namespace lakpasherpa\NepaliPaymentGateways\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use lakpasherpa\NepaliPaymentGateways\Events\PaymentCompleted;

abstract class BasePaymentController extends Controller
{
  abstract protected function getPaymentService();
  abstract protected function getPaymentMethod(): string;

  public function checkout(Request $request, $showtime_id)
  {
    $selectedSeats = session('selected_seats', []);
    $totalPrice = session('total_price', 0);
    $purchaseOrderId = 'SHOWTIME_' . $showtime_id . '_' . time();

    if (empty($selectedSeats)) {
      return redirect()->back()->with('error', 'No seats selected.');
    }

    $customerInfo = [
      'name' => Auth::user()->name,
      'email' => Auth::user()->email,
      'phone' => Auth::user()->phone ?? '9800000000',
    ];

    $paymentService = $this->getPaymentService();

    try {
      return $paymentService->pay(
        amount: $totalPrice,
        return_url: route($this->getPaymentMethod() . '.verification', ['showtime' => $showtime_id]),
        purchase_order_id: $purchaseOrderId,
        purchase_order_name: 'Showtime Booking',
        customer_info: $customerInfo
      );
    } catch (\Exception $e) {
      Log::error('Payment checkout failed', [
        'method' => $this->getPaymentMethod(),
        'error' => $e->getMessage(),
        'user_id' => Auth::id(),
        'showtime_id' => $showtime_id,
      ]);

      return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
    }
  }

  public function verification(Request $request, $showtime_id)
  {
    $paymentService = $this->getPaymentService();

    try {
      $inquiry = $this->processInquiry($request, $paymentService);

      if ($paymentService->isSuccess($inquiry)) {
        return $this->handleSuccessfulPayment($inquiry, $showtime_id, $paymentService);
      }

      return $this->handleFailedPayment($showtime_id);
    } catch (\Exception $e) {
      Log::error('Payment verification failed', [
        'method' => $this->getPaymentMethod(),
        'error' => $e->getMessage(),
        'user_id' => Auth::id(),
        'showtime_id' => $showtime_id,
      ]);

      return $this->handleFailedPayment($showtime_id);
    }
  }

  abstract protected function processInquiry(Request $request, $paymentService);

  protected function handleSuccessfulPayment($inquiry, $showtime_id, $paymentService)
  {
    // This method should be implemented by the consuming application
    // or made configurable through the package
    event(new PaymentCompleted([
      'inquiry' => $inquiry,
      'showtime_id' => $showtime_id,
      'payment_method' => $this->getPaymentMethod(),
      'user_id' => Auth::id(),
    ]));

    return redirect()->route('payment.success')
      ->with('success', 'Payment successful!');
  }

  protected function handleFailedPayment($showtime_id)
  {
    return redirect()->route('payment.failed')
      ->with('error', 'Payment failed.');
  }
}
