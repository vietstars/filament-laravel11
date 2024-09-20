<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->forceSSL();
    }

    /**
     * Force SSL for testing & production.
     *
     * @return void
     */
    protected function forceSSL()
    {
        if ($this->app->environment(['production', 'testing'])) {
            URL::forceScheme('https');
        }
    }
}
