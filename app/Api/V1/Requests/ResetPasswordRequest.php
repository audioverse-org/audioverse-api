<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.reset_password.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
