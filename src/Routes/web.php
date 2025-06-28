<?php

use Illuminate\Support\Facades\Route;
use YourName\NepalPaymentGateways\Controllers\EsewaController;
use YourName\NepalPaymentGateways\Controllers\KhaltiController;
use YourName\NepalPaymentGateways\Controllers\FonepayController;

Route::prefix('nepali-payment')->name('nepali-payment.')->group(function () {
  // eSewa Routes
  Route::get('esewa/checkout/{showtime}', [EsewaController::class, 'checkout'])->name('esewa.checkout');
  Route::get('esewa/verification/{showtime}', [EsewaController::class, 'verification'])->name('esewa.verification');

  // Khalti Routes
  Route::get('khalti/checkout/{showtime}', [KhaltiController::class, 'checkout'])->name('khalti.checkout');
  Route::get('khalti/verification/{showtime}', [KhaltiController::class, 'verification'])->name('khalti.verification');

  // FonePay Routes
  Route::get('fonepay/checkout/{showtime}', [FonepayController::class, 'checkout'])->name('fonepay.checkout');
  Route::get('fonepay/verification/{showtime}', [FonepayController::class, 'verification'])->name('fonepay.verification');
});
