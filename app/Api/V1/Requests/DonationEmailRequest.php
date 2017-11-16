<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class DonationEmailRequest extends FormRequest
{
    public function rules()
    {
        return config('avorg.donation_email.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
