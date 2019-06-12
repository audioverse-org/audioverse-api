<?php
namespace App\Transformers\Admin;

use App\License;
use App\Transformers\BaseTransformer;

class LicenseTransformer extends BaseTransformer {

   public function transform(License $license) {
      
      return [
         'id' => $license->licenseId,
         'title' => $license->title,
         'summary' => $license->summary,
         'description' => $license->description,
         'logo' => $license->logo,
         'permitsSales' => $license->permitsSales,
         'lang' => $license->lang,
         'notes' => $license->notes,
         'created' => $license->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($license),
      ];
   }
}