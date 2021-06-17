<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class AgreementRequest extends FormRequest {
   
   public function rules() {
      
      $rules = [
         'title' =>'required',
         'summary' => 'present',
         'ownerId' => 'present|numeric',
         'licenseId' => 'present|numeric',
         'lang' => 'required',
         'hidden' => 'required|numeric|max:1|min:0',
         'notes' => 'present',
         'retired' => 'required|numeric|max:1|min:0',
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
