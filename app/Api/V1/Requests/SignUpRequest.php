<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.sign_up.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
