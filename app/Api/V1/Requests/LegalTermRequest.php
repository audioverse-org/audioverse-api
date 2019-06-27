<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class LegalTermRequest extends FormRequest {
   
   public function rules() {
      $rules = [
         'label' => 'required',
         'terms' => 'present',
         'formType' => 'required',
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

   public function authorize() {
      return true;
   }
}
