<?php
namespace App\Transformers\Admin;

use App\Agreement;
use App\Transformers\BaseTransformer;
use App\Transformers\Joins\OwnerIncludeTransformer;
use App\Transformers\Joins\LicenseIncludeTransformer;

class AgreementTransformer extends BaseTransformer {

   protected $defaultIncludes = [
      'owner',
      'license'
   ];

   public function transform(Agreement $agreement) {
      
      return [
         'id' => $agreement->agreementId,
         'title' => $agreement->title,
         'summary' => $agreement->summary,
         'ownerId' => $agreement->ownerId,
         'licenseId' => $agreement->licenseId,
         'presentationCount' => $agreement->recordings()->count(),
         'lang' => $agreement->lang,
         'hiddenBySelf' => $agreement->hiddenBySelf,
         'hidden' => $agreement->hidden,
         'notes' => $agreement->notes,
         'retired' => $agreement->retired,
         'created' => $agreement->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($agreement),
      ];
   }

   public function includeOwner(Agreement $agreement) {

      $owner = $agreement->owner;
      if ($owner) {
         return $this->item($owner, new OwnerIncludeTransformer, 'include');
      }
   }

   public function includeLicense(Agreement $agreement) {

      $license = $agreement->license;
      if ($license) {
         return $this->item($license, new LicenseIncludeTransformer, 'include');
      }
   }
}