<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class TagCategoryRequest extends FormRequest
{
    public function rules()
    {
      $rules = [
         'name' => 'required',
         'lang' => 'required',
         'contentType' => 'required',
      ];

      if ($this->method() == 'POST') {
         return $rules;
      } else if ($this->method() == 'PUT') {
         $rules['id'] = 'required|numeric';
         return $rules;
      } else if ($this->method() == 'DELETE') {
         return ['id' => 'required|numeric'];
      } 
    }

    public function authorize()
    {
        return true;
    }
}