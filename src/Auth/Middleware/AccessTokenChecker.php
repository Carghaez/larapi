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
        Authenticate $auth
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
    public function handle($request, Closure $next)
    {
        if ($this->app->environment() !== 'testing') {
            try {
                return $this->auth->handle($request, $next, 'auth');
            } catch (AuthenticationException $e) {
                throw new UnauthorizedHttpException('Challenge');
            }
        }

        return $next($request);
    }
}
