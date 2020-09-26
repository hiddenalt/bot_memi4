<?php

namespace App\Providers;

use App\System\ApplicationRoles;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

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

        // Laravel Passport routes
        Passport::routes(function($router){
            $router->forAccessTokens();
        });

        // Allow every permission to superadmin
        // Remove if unnecessary and define custom permissions in console.php
        // Used only with auth()!
        Gate::before(function ($user, $ability) {
            return $user->hasRole(ApplicationRoles::SUPERADMIN) ? true : null;
        });
    }
}
