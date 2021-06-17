<?php
namespace App\Transformers\Admin;

use App\Presenter;
use App\Transformers\BaseTransformer;

class PresenterTransformer extends BaseTransformer {

   public function transform(Presenter $presenter) {

      return [
         'id' => $presenter->personId,
         'evalsRequired' => $presenter->evalsRequired,
         'salutation' => $presenter->salutation,
         'givenName' => $presenter->givenName,
         'surname' => $presenter->surname,
         'suffix' => $presenter->suffix,
         'letters' => $presenter->letters,
         'hiragana' => $presenter->hiragana,
         'nameGnfCasual' => $presenter->nameGnfCasual,
         'nameSnfCasual' => $presenter->nameSnfCasual,
         'nameGnfFormal' => $presenter->nameGnfFormal,
         'nameSnfFormal' => $presenter->nameSnfFormal,
         'photo' => [
            'small' => $presenter->logoSmall,
            'medium' => $presenter->logoMedium,
            'large' => $presenter->logoLarge,
         ],
         'summary' => $presenter->summary,
         'description' => $presenter->description,
         'website' => $presenter->website,
         'publicAddress' => $presenter->publicAddress,
         'publicPhone' => $presenter->publicPhone,
         'publicEmail' => $presenter->publicEmail,
         'contactName' => $presenter->contactName,
         'contactAddress' => $presenter->contactAddress,
         'contactPhone' => $presenter->contactPhone,
         'contactEmail' => $presenter->contactEmail,
         'created' => $presenter->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($presenter),
         'lang' => $presenter->lang,
         'hiddenBySelf' => $presenter->hiddenBySelf,
         'hidden' => $presenter->hidden,
         'notes' => $presenter->notes,
      ];
   }
}