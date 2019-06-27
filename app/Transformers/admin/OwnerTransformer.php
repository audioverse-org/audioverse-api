<?php
namespace App\Transformers\Admin;

use App\Owner;
use App\Transformers\BaseTransformer;

class OwnerTransformer extends BaseTransformer {

   public function transform(Owner $owner) {
      
      return [
         'id' => $owner->ownerId,
         'title' => $owner->title,
         'summary' => $owner->summary,
         'description' => $owner->description,
         'logo' => $owner->logo,
         'location' => $owner->location,
         'website' => $owner->website,
         'publicAddress' => $owner->publicAddress,
         'publicPhone' => $owner->publicPhone,
         'publicEmail' => $owner->publicEmail,
         'contactName' => $owner->contactName,
         'contactAddress' => $owner->contactAddress,
         'contactPhone' => $owner->contactPhone,
         'contactEmail' => $owner->contactEmail,
         'lang' => $owner->lang,
         'notes' => $owner->notes,
         'created' => $owner->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($owner),
      ];
   }
}