<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class RecordingRequest extends FormRequest
{
   public function rules() {
      
      $rules = [
         'sponsorId' => 'required',
         'agreementId' => 'required|numeric',
         'copyrightYear' => 'required|numeric',
         'isComplete' => 'required|numeric|min:0|max:1',
         'title' => 'required',
         'publishDate' => 'required',
         'lang' => 'required',
         'hidden' => 'required',
         'downloadDisabled' => 'required',
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
