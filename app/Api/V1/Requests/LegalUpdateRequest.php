<?php
namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class LegalUpdateRequest extends FormRequest {
   
   public function rules() {
      return [
         'id' => 'required',
         'agreementId' => 'required',
         'copyrightYear' => 'present',
         'notes' => 'present',
         'legalStatus' => 'required',
      ];
   }

   public function authorize() {
      return true;
   }
}
