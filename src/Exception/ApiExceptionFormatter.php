<?php

namespace Carghaez\Larapi\Exception;

use Exception;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\BaseFormatter;

class ApiExceptionFormatter extends BaseFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        if (!$response->getStatusCode()) {
            $response->setStatusCode(404);
        }
        $data = $response->getData(true);

        if ($this->debug) {
            $data = array_merge($data, [
                'code'   => $e->getCode(),
                'message'   => $e->getMessage(),
                'exception' => (string) $e,
                'line'   => $e->getLine(),
                'file'   => $e->getFile()
            ]);
        } else {
            $data['message'] = $e->getMessage();
        }

        $response->setData($data);
    }
}
