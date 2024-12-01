<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use Adaojunior\PassportSocialGrant\SocialGrantUserProvider;
use App\SocialGrant\UserProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind( SocialGrantUserProvider::class, UserProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set the expiration time for the tokens
        Passport::tokensExpireIn(now()->addMinute((int) config('auth.passport.access_token_expiration')));
        Passport::refreshTokensExpireIn(now()->addDays((int) config('auth.passport.refresh_token_expiration')));
    }
}
