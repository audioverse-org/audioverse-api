<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class SeriesRequest extends FormRequest
{
   public function rules()
   {
      return [
         'contentType' => 'required|numeric',
         'sponsorId' => 'required|numeric|min:0',
         'conferenceId' => 'required|numeric|min:0',
         'hiragana' => 'present',
         'title' => 'required',
         'summary' => 'present',
         'description' => 'present',
         'logo' => 'present',
         'isbn' => 'present',
         'lang' => 'required',
         'hidden' => 'required|numeric|max:1|min:0',
         'notes' => 'present',
      ];
   }

   public function authorize()
   {
      return true;
   }
}
