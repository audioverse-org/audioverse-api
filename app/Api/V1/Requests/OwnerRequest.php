<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class OwnerRequest extends FormRequest {
   
   public function rules() {
      
      $rules = [
         'title' =>'required',
         'summary' => 'present',
         'description' => 'present',
         'logo' => 'present',
         'location' => 'present',
         'website' => 'present',
         'publicAddress' => 'present',
         'publicPhone' => 'present',
         'publicEmail' => 'present',
         'contactName' => 'present',
         'contactAddress' => 'present',
         'contactPhone' => 'present',
         'contactEmail' => 'present',
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
