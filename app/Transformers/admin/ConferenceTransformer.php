<?php
namespace App\Transformers\Admin;

use App\Conference;
use App\Transformers\Joins\SponsorIncludeTransformer;
use League\Fractal\TransformerAbstract;

class ConferenceTransformer extends TransformerAbstract {

   protected $defaultIncludes = [
      'sponsor',
   ];

   public function transform(Conference $conference) {
      return [
         'id' => $conference->conferenceId,
         'contentType' => $conference->contentType,
         'sponsorId' => $conference->sponsorId,
         'title' => $conference->title,
         'summary' => $conference->summary,
         'description' => $conference->description,
         'location' => $conference->location,
         'logo' => [
               'small' => $conference->logoSmall,
               'medium' => $conference->logoMedium,
               'large' => $conference->logoLarge,
         ],
         'created' => $conference->created,
         'modified' => $conference->modified,
         'lang' => $conference->lang,
         'hiddenBySelf' => $conference->hidden,
         'hiddenBySponsor' => $conference->hidden,
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