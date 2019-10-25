<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class TopicRequest extends FormRequest
{
   public function rules()
   {
      $rules = [
         'title' => 'required',
         'parentTopicId' => 'required',
         'lang' => 'required',
         'hidden' => 'required',
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