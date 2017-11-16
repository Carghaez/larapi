<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Auth\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Illuminate\Contracts\Auth\Factory as Auth;

/**
 * AccessTokenChecker.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class AccessTokenChecker
{
    private $app;

    private $auth;

    /**
     * Constructor.
     *
     * @param Application     $app     The application
     * @param Authenticate    $auth    The authenticate info
     */
    public function __construct(
        Application $app,
        Auth $auth
    ) {
        $this->app = $app;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming Preflight request on restricted routes.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'api')
    {
        var_dump($guard);
        dd($request);
        if ($this->auth->guard($guard)->guest()) {
            throw new UnauthorizedHttpException('Challenge');
        }

        return $next($request);
    }
}
