<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\Auth\SignInWithAppleProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    
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
