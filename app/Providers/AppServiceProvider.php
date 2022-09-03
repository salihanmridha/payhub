<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Http\Contracts\FeeCalculatorInterface;
use App\Http\Services\FeeCalculatorService;

use App\Http\Contracts\FileParsingInterface;
use App\Http\Services\FileParsingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(
          FeeCalculatorInterface::class,
          FeeCalculatorService::class
      );
      $this->app->bind(
          FileParsingInterface::class,
          FileParsingService::class
      );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
