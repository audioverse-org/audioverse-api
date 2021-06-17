<?php
namespace App\Transformers\Admin;

use App\Conference;
use App\Transformers\BaseTransformer;
use App\Transformers\Joins\SponsorIncludeTransformer;

class ConferenceTransformer extends BaseTransformer {

   protected $defaultIncludes = [
      'sponsor',
   ];

   public function transform(Conference $conference) {
      
      return [
         'id' => $conference->conferenceId,
         'contentType' => $conference->contentType,
         'sponsorId' => $conference->sponsorId,
         'hiragana' => $conference->hiragana,
         'title' => $conference->title,
         'summary' => $conference->summary,
         'description' => $conference->description,
         'location' => $conference->location,
         'logo' => [
            'small' => $conference->logoSmall,
            'medium' => $conference->logoMedium,
            'large' => $conference->logoLarge,
         ],
         'created' => $conference->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($conference),
         'lang' => $conference->lang,
         'hiddenBySelf' => $conference->hiddenBySelf,
         'hiddenBySponsor' => $conference->hiddenBySponsor,
         'hidden' => $conference->hidden,
         'notes' => $conference->notes,
      ];
   }

    public function includeSponsor(Conference $conference) {

      $sponsor = $conference->sponsor;
      if ($sponsor) {
         return $this->item($sponsor, new SponsorIncludeTransformer, 'include');
      }
  }
}