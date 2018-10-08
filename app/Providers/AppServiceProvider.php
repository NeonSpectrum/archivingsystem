<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot() {
    $whitelist = ['127.0.0.1', '::1'];

    if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
      URL::forceScheme('https');
    }
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register() {
    //
  }
}
