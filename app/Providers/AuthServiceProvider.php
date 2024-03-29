<?php

namespace App\Providers;

use App\Auth\EloquentWithTokenUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('token', function ($app, array $config) {
            return new EloquentWithTokenUserProvider($app['hash'], $config['model'], $config['modelTokens']);
        });
    }
}
