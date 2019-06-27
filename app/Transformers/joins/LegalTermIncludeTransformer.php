<?php
namespace App\Transformers\Joins;

use League\Fractal\TransformerAbstract;
use App\LegalTerm;

class LegalTermIncludeTransformer extends TransformerAbstract {

    public function transform(LegalTerm $legalTerm) {

      return [
         'label' => $legalTerm->label,
         'formType' => $legalTerm->formType
      ];
    }
}