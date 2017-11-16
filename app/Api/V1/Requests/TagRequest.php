<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.tags.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}