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
        $response->setData(
            $this->handle($response->getData(true), $e)
        );
        return $response;
    }

    protected function handle($data, $e)
    {
        if ($this->debug) {
            $data = array_merge($data, [
                'status' => $e->getStatusCode(),
                'code'   => $e->getCode(),
                'message'   => $e->getMessage(),
                'detail' => (string) $e,
                'line'   => $e->getLine(),
                'file'   => $e->getFile()
            ]);
        } else {
            $data['message'] = $e->getMessage();
        }
        return $data;
    }
}
