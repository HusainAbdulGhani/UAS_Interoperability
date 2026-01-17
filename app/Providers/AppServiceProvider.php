<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $token = str_replace('Bearer ', '', $request->header('Authorization'));
                return \App\Models\User::where('api_token', $token)->first();
            }
        });
    }
}