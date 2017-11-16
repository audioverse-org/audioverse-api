<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.forgot_password.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
