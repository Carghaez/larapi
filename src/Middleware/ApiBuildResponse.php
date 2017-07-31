<?php

namespace Carghaez\Larapi\Middleware;

use Closure;
use Illuminate\Http\Response;

use Carbon\Carbon;

class ApiBuildResponse
{
    /**
     - Handle an incoming request.
     *
     - @param  \Illuminate\Http\Request  $request
     - @param  \Closure  $next
     - @param  string|null  $guard
     - @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);

        if($response->headers->get('content-type') == 'application/json')
        {
            $baseResponse = [
                'error' => !$response->isSuccessful(),
                'status' => $response->status(),
                'results' => $response->getData(true)
            ];
            $response->setData($baseResponse);
        }

        return $response;
    }
}
