<?php

namespace Carghaez\Larapi\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new UnprocessableEntityHttpException($validator->errors()->toJson());
    }

    protected function failedAuthorization()
    {
        // The request was valid, but the server is refusing action.
        // The user might not have the necessary permissions for a resource, or may need an account of some sort.
        throw new HttpException(403);
    }
}
