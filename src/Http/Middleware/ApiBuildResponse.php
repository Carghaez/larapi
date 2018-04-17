<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

use Carbon\Carbon;

/**
 * ApiBuildResponse.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ApiBuildResponse
{
    protected function isValidationError($data)
    {
        if (isset($data[0]['status']) && $data[0]['status'] == 422) {
            return true;
        }
        return false;
    }

    protected function is_array_assoc($arr)
    {
        if (!$arr || [] === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);

        switch($response->headers->get('content-type'))
        {
            case 'application/json':
                try {
                    $results = $response->getData(true);
                } catch(\Exception $e) {
                    $results = json_encode($response);
                }

                $error = !$response->isSuccessful();
                $status = $response->status();

                if (!$error && $this->isValidationError($results)) {
                    $error = true;
                    $status = 422;
                }

                $message = '';
                if (is_string($results)) {
                    $message = $results;
                    $results = null;
                }
                if (isset($results['message']) && isset($results['data'])) {
                    $message = $results['message'];
                    $results = $results['data'];
                }

                $baseResponse = [
                    'error' => $error,
                    'status' => $status,
                    'message' => $message,
                    'results' => $results
                ];
                $response->setData($baseResponse);
                break;
            default:
                break;
        }

        return $response;
    }
}
