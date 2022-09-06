<?php

namespace App\Providers;

use App\Services\TransferMoneyInterface;
use App\Services\TransferMoneyService;
use Illuminate\Support\ServiceProvider;

class TransferMonetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TransferMoneyInterface::class,fn() =>new TransferMoneyService());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
