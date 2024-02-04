<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\SocialiteServiceProvider;
use App\Http\Controllers\Auth\SignInWithAppleProvider;

class AppleProvider extends SocialiteServiceProvider
{
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'apple',
            function ($app) use ($socialite) {
                $config = $app['config']['services.apple'];
                return $socialite->buildProvider(SignInWithAppleProvider::class, $config);
            }
        );
    }
}
