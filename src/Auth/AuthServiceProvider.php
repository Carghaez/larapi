<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Auth;

use Carbon\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * AuthServiceProvider.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
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
     * Get token lifetime from social config.
     *
     * @return int token lifetime in minutes
     */
    protected function getTokenLifetime()
    {
        $socialConfig = $this->app['config']->get('social');
        if ($socialConfig === null) {
            return 20;
        }
        return $socialConfig['token_lifetime'];
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(function ($router) {
            $router->forAccessTokens();
            // Uncomment for allowing personal access tokens
            $router->forPersonalAccessTokens();
            $router->forTransientTokens();
        });

        Passport::enableImplicitGrant();

        Passport::tokensExpireIn(Carbon::now()->addMinutes($this->getTokenLifetime()));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(10));
    }
}
