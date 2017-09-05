<?php

namespace Carghaez\Larapi\Exception\Formatters;

use Exception;
use Illuminate\Http\JsonResponse;
use Optimus\Heimdal\Formatters\BaseFormatter;

class ExceptionFormatter extends BaseFormatter
{
    public function format(JsonResponse $response, Exception $e, array $reporterResponses)
    {
        $response->setStatusCode(500);
        $data = [
            $this->handle($response->getData(true), $e)
        ];
        $response->setData($data);
        return $response;
    }

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
                'status' => $statusCode,
                'code'   => $e->getCode(),
                'type' => get_class($e),
                'message'   => $e->getMessage(),
                'detail' => (string) $e,
                'line'   => $e->getLine(),
                'file'   => $e->getFile()
            ], $data);
        } else {
            $data['message'] = $e->getMessage();
        }
        return (object) $data;
    }
}
