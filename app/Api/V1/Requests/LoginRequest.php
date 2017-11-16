<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.login.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
