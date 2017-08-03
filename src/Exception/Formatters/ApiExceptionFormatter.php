<?php

namespace Carghaez\Larapi\Exception\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\BaseFormatter;

class ApiExceptionFormatter extends BaseFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        if ($e->getStatusCode()) {
            $response->setStatusCode($e->getStatusCode());
        }
        $data = $response->getData(true);

        if ($this->debug) {
            $data = array_merge($data, [
                'code'   => $e->getCode(),
                'title' => 'Api error',
                'detail'   => $e->getMessage(),
                'exception' => (string) $e,
                'line'   => $e->getLine(),
                'file'   => $e->getFile()
            ]);
        } else {
            $data['detail'] = $e->getMessage();
        }

        $response->setData($data);
    }
}
