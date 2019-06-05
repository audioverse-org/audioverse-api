<?php
namespace App\Transformers\Admin;

use App\Sponsor;
use App\Transformers\BaseTransformer;

class SponsorTransformer extends BaseTransformer {

    public function transform(Sponsor $sponsor) {

      return [
         'id' => $sponsor->sponsorId,
         'hiragana' => $sponsor->hiragana,
         'title' => $sponsor->title,
         'summary' => $sponsor->summary,
         'description' => $sponsor->description,
         'location' => $sponsor->location,
         'website' => $sponsor->website,
         'logo' => [
            'small' => $sponsor->logoSmall,
            'medium' => $sponsor->logoMedium,
            'large' => $sponsor->logoLarge,
         ],
         'location' => $sponsor->location,
         'website' => $sponsor->website,
         'publicAddress' => $sponsor->publicAddress,
         'publicPhone' => $sponsor->publicPhone,
         'publicEmail' => $sponsor->publicEmail,
         'contactName' => $sponsor->contactName,
         'contactAddress' => $sponsor->contactAddress,
         'contactPhone' => $sponsor->contactPhone,
         'created' => $sponsor->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($sponsor),
         'lang' => $sponsor->lang,
         'hiddenBySelf' => $sponsor->hiddenBySelf,
         'hidden' => $sponsor->hidden,
         'notes' => $sponsor->notes
      ];
    }
}