<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        //We are defining an Auth guard driver called my_api_guard
        $this->app['auth']->viaRequest('my_api_guard', function ($request) {
            /*
             * If the http request contains a URI parameter called api_token that has the same value as
             * the environment variable called API_TOKEN stored in the .env file of the project, then we allow access
             * otherwise an unauthorized msg will be displayed by Lumen
             */

            if ($request->input('api_token') == env('API_TOKEN')) {
                //return User::where('api_token', $request->input('api_token'))->first();
                return "api_token received";
            }
        });
    }
}
