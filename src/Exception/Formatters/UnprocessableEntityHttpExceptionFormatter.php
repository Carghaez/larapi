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
use Optimus\Heimdal\Formatters\BaseFormatter;

/**
 * UnprocessableEntityHttpExceptionFormatter.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class UnprocessableEntityHttpExceptionFormatter extends HttpExceptionFormatter
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
        // Laravel validation errors will return JSON string
        $decoded = json_decode($e->getMessage(), true);
        // Message was not valid JSON
        // This occurs when we throw UnprocessableEntityHttpExceptions
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Mimick the structure of Laravel validation errors
            $decoded = [[$e->getMessage()]];
        }
        // Laravel errors are formatted as {"field": [/*errors as strings*/]}
        $data = [];
        foreach ($decoded as $key => $item) {
            if (!is_array($item)) {
                $item = [$item];
            }
            array_push(
                $data,
                $this->handle([
                    'status' => 422,
                    'type' => $key,
                    'message'   => $item,
                ], $e)
            );
        }

        $response->setData($data);

        return $response;
    }
}
