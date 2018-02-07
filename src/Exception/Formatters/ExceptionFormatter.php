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
 * ExceptionFormatter.
 *
 * @author Gaetano Carpinato <gaetanocarpinato@gmail.com>
 */
class ExceptionFormatter extends BaseFormatter
{
    protected function handle($data, $e)
    {
        $statusCode = 500;
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        }
        if ($data['status'] === 'error') {
            unset($data['status']);
        }
        if ($this->debug) {
            $data = array_merge([
                'status'    => $statusCode,
                'type'      => get_class($e),
                'message'   => $e->getMessage(),
                'code'      => $e->getCode(),
                'trace'     => $e->getTrace(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile()
            ], $data);
        } else {
            $data['status'] = $statusCode;
            $data['type'] = get_class($e);
            $data['message'] = $e->getMessage();
        }
        return (object) $data;
    }

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
        $response->setStatusCode(500);
        $data = [
            $this->handle($response->getData(true), $e)
        ];
        $response->setData($data);
        return $response;
    }
}
