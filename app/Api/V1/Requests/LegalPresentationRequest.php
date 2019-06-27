<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class LegalPresentationRequest extends FormRequest {
   
   public function rules() {
      $rules = [
        
      ];

      if ($this->method() == 'PUT') {
         $rules['id'] = 'required|numeric';
         return $rules;
      }
   }

   public function authorize() {
      return true;
   }
}
