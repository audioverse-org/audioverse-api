<?php
namespace App\Transformers\Joins;

use App\Agreement;
use App\Transformers\BaseTransformer;

class AgreementIncludeTransformer extends BaseTransformer {

   public function transform(Agreement $agreement) {
      
      return [
         'id' => $agreement->agreementId,
         'title' => $agreement->title,
      ];
   }
}