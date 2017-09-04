<?php

namespace Carghaez\Larapi\Auth\Requests;

use Carghaez\Larapi\ApiRequest;

class LoginRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|min:3'
        ];
    }
}
