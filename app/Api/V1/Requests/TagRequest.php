<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class TagRequest extends FormRequest
{
   public function rules()
   {
      $rules = [
         'name.*' => 'required|string|distinct|min:3'
      ];

      if ($this->method() == 'POST') {
         return $rules;
      } else if ($this->method() == 'PUT') {
         $rules['id'] = 'required|numeric';
         return $rules;
      } else if ($this->method() == 'DELETE') {
         return ['id' => 'required|numeric'];
      } 
      
      return $rules;
   }

   public function authorize()
   {
      return true;
   }
}