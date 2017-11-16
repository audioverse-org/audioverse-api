<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class AddVideoFlavorRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.add_video_flavor.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
