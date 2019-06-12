<?php
namespace App\Transformers\Admin;

use App\LegalRelease;
use App\Transformers\BaseTransformer;

class LegalReleaseTransformer extends BaseTransformer {

   public function transform(LegalRelease $legalRelease) {
      
      // TODO calculate coverage
      return [
         'id' => $legalRelease->id,
         'conferenceId' => $legalRelease->conferenceId,
         'termsId' => $legalRelease->termsId,
         'personId' => $legalRelease->personId,
         'recordingId' => $legalRelease->recordingId,
         'agree' => $legalRelease->agree,
         'firstName' => $legalRelease->firstName,
         'lastName' => $legalRelease->lastName,
         'email' => $legalRelease->email,
         'phone' => $legalRelease->phone,
         'address' => $legalRelease->address,
         'address2' => $legalRelease->address2,
         'municipality' => $legalRelease->municipality,
         'province' => $legalRelease->province,
         'postalCode' => $legalRelease->postalCode,
         'country' => $legalRelease->country,
         'comments' => $legalRelease->comments,
         'created' => $legalRelease->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($legalRelease),
      ];
   }
}