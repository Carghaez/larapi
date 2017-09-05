<?php

namespace Carghaez\Larapi\Middleware;

use Closure;
use Illuminate\Http\Response;

use Carbon\Carbon;

class ApiBuildResponse
{
    protected function isValidationError($data)
    {
        if (isset($data[0]['status']) && $data[0]['status'] == 422) {
            return true;
        }
        return false;
    }

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
            $status = $response->status();

            if (!$error && $this->isValidationError($results)) {
                $error = true;
                $status = 422;
            }
            // if (isset($results['status']) && $results['status'] == 'error') {
            //     $error = true;
            //     unset($results['status']);
            //     if ($response->status() !== 200) {
            //         $results['status'] = $response->status();
            //     }
            //     $results = [
            //         $results
            //     ];
            // }

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
                'status' => $status,
                'message' => $message,
                'results' => $results
            ];
            $response->setData($baseResponse);
        }

        return $response;
    }
}