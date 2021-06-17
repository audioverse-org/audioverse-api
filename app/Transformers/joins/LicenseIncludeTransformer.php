<?php
namespace App\Transformers\Joins;

use App\License;
use App\Transformers\BaseTransformer;

class LicenseIncludeTransformer extends BaseTransformer {

   public function transform(License $license) {
      
      return [
         'id' => $license->licenseId,
         'title' => $license->title,
      ];
   }
}