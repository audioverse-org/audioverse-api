<?php
namespace App\Transformers\Admin;

use App\Series;
use App\Transformers\BaseTransformer;
use App\Transformers\Joins\ConferenceIncludeTransformer;
use App\Transformers\Joins\SponsorIncludeTransformer;

class SeriesTransformer extends BaseTransformer {
   
   protected $defaultIncludes = [
      'sponsor',
      'conference'
   ];

   public function transform(Series $series) {

      return [
         'id' => $series->seriesId,
         'contentType' => $series->contentType,
         'sponsorId' => $series->sponsorId,
         'conferenceId' => $series->conferenceId,
         'hiragana' => $series->hiragana,
         'title' => $series->title,
         'summary' => $series->summary,
         'description' => $series->description,
         'logo' => [
            'small' => $series->logoSmall,
            'medium' => $series->logoMedium,
            'large' => $series->logoLarge,
         ],
         'isbn' => $series->isbn,
         'created' => $series->created->toDateTimeString(),
         'modified' => $this->checkModifiedDateIfValid($series),
         'lang' => $series->lang,
         'hiddenBySelf' => $series->hiddenBySelf,
         'hiddenByConference' => $series->hiddenByConference,
         'hiddenBySponsor' => $series->hiddenBySponsor,
         'hidden' => $series->hidden,
         'notes' => $series->notes
      ];
   }

   public function includeSponsor(Series $series) {

      $sponsor = $series->sponsor;
      if ($sponsor) {
         return $this->item($sponsor, new SponsorIncludeTransformer, 'include');
      }
  }

   public function includeConference(Series $series) {

      $conference = $series->conference;
      if ($conference) {
         return $this->item($conference, new ConferenceIncludeTransformer, 'include');
      }
   }
}