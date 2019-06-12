<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class LicenseRequest extends FormRequest {
   
   public function rules() {
      
      $rules = [
         'title' =>'required',
         'summary' => 'present',
         'description' => 'present',
         'logo' => 'present',
         'permitsSales' => 'required|numeric|max:1|min:0',
         'lang' => 'required',
         'notes' => 'present',
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
