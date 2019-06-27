<?php
namespace App\Transformers\Admin;

use App\LegalRelease;
use App\Transformers\BaseTransformer;
use App\Transformers\Joins\ConferenceIncludeTransformer;
use App\Transformers\Joins\PresenterIncludeTransformer;
use App\Transformers\Joins\LegalTermIncludeTransformer;

class LegalReleaseTransformer extends BaseTransformer {

   public function transform(LegalRelease $legalRelease) {

      $conferenceCoverage = 'N/A';
      $presenterFullName = 'None';      
      $legalTerm = '';
      $recordingCoverage = 'N/A';

      $term = $legalRelease->term;
      if ($term) {
         $recordingCoverage = $term->formType;
         $legalTerm = $term->label;
      }

      $presenter = $legalRelease->presenter;
      if ($presenter) {
         $presenterFullName = $presenter->surname . ', ' . $presenter->givenName;
      }

      if ($recordingCoverage == 'Master') {
         $conferenceCoverage = 'Master';
      } else {
         $conference = $legalRelease->conference;
         if ($conference) {
            $conferenceCoverage = $conference->title;
         } 
      }

      return [
         'id' => $legalRelease->id,
         'conferenceId' => $legalRelease->conferenceId,
         'termsId' => $legalRelease->termsId,
         'personId' => $legalRelease->personId,
         'recordingId' => $legalRelease->recordingId,
         'agree' => $legalRelease->agree,
         'speaker' => $legalRelease->lastName .', '.$legalRelease->firstName,
         'presenter' => $presenterFullName,
         'legalTerm' => $legalTerm,
         'recordingCoverage' => $recordingCoverage,
         'conferenceCoverage' => $conferenceCoverage,
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