<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;


class JwtAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        require_once app_path().'\Helpers\JwtAuth.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */

    public function boot()
    {
         //$this->registerPolicies();
       
    }
}
