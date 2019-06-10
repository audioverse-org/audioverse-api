<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class ConferenceRequest extends FormRequest {

   public function rules() {
      
      $rules = [
         'contentType' => 'required|numeric',
         'sponsorId' => 'required|numeric|min:0',
         'hiragana' => 'present',
         'title' => 'required',
         'summary' => 'present',
         'description' => 'present',
         'logo' => 'present',
         'location' => 'present',
         'lang' => 'required',
         'hidden' => 'required|numeric|max:1|min:0',
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
