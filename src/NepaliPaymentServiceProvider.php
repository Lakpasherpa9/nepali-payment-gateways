<?php

namespace lakpasherpa\NepaliPaymentGateways;

use Illuminate\Support\ServiceProvider;
use YourName\NepalPaymentGateways\Services\Esewa;
use YourName\NepalPaymentGateways\Services\Khalti;
use YourName\NepalPaymentGateways\Services\Fonepay;

class NepaliPaymentServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/nepal-payment.php', 'nepal-payment');

    // Bind services
    $this->app->bind('esewa', function () {
      return new Esewa();
    });

    $this->app->bind('khalti', function () {
      return new Khalti();
    });

    $this->app->bind('fonepay', function () {
      return new Fonepay();
    });
  }

  public function boot()
  {
    // Publish config
    $this->publishes([
      __DIR__ . '/../config/nepal-payment.php' => config_path('nepal-payment.php'),
    ], 'config');

    // Publish migrations
    $this->publishes([
      __DIR__ . '/../database/migrations/' => database_path('migrations'),
    ], 'migrations');

    // Publish views
    $this->publishes([
      __DIR__ . '/../resources/views' => resource_path('views/vendor/nepal-payment'),
    ], 'views');

    // Load routes
    $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

    // Load views
    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nepal-payment');

    // Load migrations
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
  }
}
