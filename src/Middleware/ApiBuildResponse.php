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
            $results = $response->getData(true);

            $error = !$response->isSuccessful();

            if (!$error && isset($results['errors'])) {
                $error = true;
            }
            if (isset($results['status']) && $results['status'] == 'error') {
                $error = true;
                unset($results['status']);
                if ($response->status() !== 200) {
                    $results['status'] = $response->status();
                }
                $results = [
                    $results
                ];
            }

            $message = '';
            if (is_string($results)) {
                $message = $results;
                $results = null;
            }
            if (is_object($results)) {
                $message = $results->message;
                $results = $results->data;
            }

            $baseResponse = [
                'error' => $error,
                'status' => $response->status(),
                'message' => $message,
                'results' => $results
            ];
            $response->setData($baseResponse);
        }

        return $response;
    }
}
