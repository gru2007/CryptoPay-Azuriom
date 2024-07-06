<?php

namespace Azuriom\Plugin\Crypto\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\Crypto\CryptoMethod;

class CryptoServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViews();

        $this->loadTranslations();

        payment_manager()->registerPaymentMethod('cryptopay', CryptoMethod::class);
    }
}
