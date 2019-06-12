<?php
namespace App\Transformers\Admin;

use App\LegalTerm;
use App\Transformers\BaseTransformer;

class LegalTermTransformer extends BaseTransformer {

   public function transform(LegalTerm $legalTerm) {
      
      return [
         'id' => $legalTerm->id,
         'label' => $legalTerm->label,
         'terms' => $legalTerm->terms,
         'formType' => $legalTerm->formType,
         'created' => $legalTerm->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($legalTerm),
      ];
   }
}