<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class LegalReleaseRequest extends FormRequest {
   
   public function rules() {
      $rules = [
         'conferenceId' => 'present',
         'termsId' => 'present',
         'personId' => 'present',
         'recordingId' => 'present',
         'agree' => 'present',
         'firstName' => 'present',
         'lastName' => 'present',
         'email' => 'present',
         'phone' => 'present',
         'address' => 'present',
         'address2' => 'present',
         'municipality' => 'present',
         'province' => 'present',
         'postalCode' => 'present',
         'country' => 'present',
         'comments' => 'present',
         'copyContacts' => 'present|numeric|max:1|min:0',
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
