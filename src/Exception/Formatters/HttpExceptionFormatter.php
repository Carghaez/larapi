<?php

/*
 * This file is part of the Larapi package.
 *
 * (c) Gaetano Carpinato <gaetanocarpinato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carghaez\Larapi\Exception\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Carghaez\Larapi\Exception\Formatters\ExceptionFormatter;

/**
 * HttpExceptionFormatter.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class HttpExceptionFormatter extends ExceptionFormatter
{
    /**
     * Format Exception.
     *
     * @param \Illuminate\Http\JsonResponse $response
     * @param \Exception $e
     * @param array $reporterResponses
     *
     * @return mixed
     */
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        $response = parent::format($response, $e, $reporterResponses);

        if (count($headers = $e->getHeaders())) {
            $response->headers->add($headers);
        }

        $response->setStatusCode($e->getStatusCode());

        return $response;
    }
}
