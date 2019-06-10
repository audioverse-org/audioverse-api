<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class PresenterRequest extends FormRequest
{
   public function rules() {
      
      $rules = [
         'evalsRequired' => 'required|numeric|max:1|min:0',
         'salutation' => 'present',
         'givenName' => 'required',
         'surname' => 'required',
         'suffix' => 'present',
         'letters' => 'present',
         'hiragana' => 'present',
         'photo' => 'present',
         'summary' => 'present',
         'description' => 'present',
         'website' => 'present',
         'publicAddress' => 'present',
         'publicPhone' => 'present',
         'publicEmail' => 'present|email',
         'contactName' => 'present',
         'contactAddress' => 'present',
         'contactPhone' => 'present',
         'contactEmail' => 'present|email',
         'hidden' => 'required|numeric|max:1|min:0',
         'lang' => 'required',
         'notes' => 'present'
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
