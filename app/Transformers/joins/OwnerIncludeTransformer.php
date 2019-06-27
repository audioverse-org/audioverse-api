<?php
namespace App\Transformers\Joins;

use App\Owner;
use App\Transformers\BaseTransformer;

class OwnerIncludeTransformer extends BaseTransformer {

   public function transform(Owner $owner) {
      
      return [
         'id' => $owner->ownerId,
         'title' => $owner->title,
      ];
   }
}