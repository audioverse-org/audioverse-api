<?php
namespace App\Transformers\Admin;

use App\Recording;
use App\Transformers\Joins\AgreementIncludeTransformer;
use App\Transformers\Joins\ConferenceIncludeTransformer;
use App\Transformers\Joins\PresenterIncludeTransformer;
use App\Transformers\Joins\SponsorIncludeTransformer;
use App\Transformers\Joins\SeriesIncludeTransformer;
use League\Fractal\TransformerAbstract;

class LegalRecordingTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
      'agreement',
      'sponsor',
      'series',
      'conference',
      'presenters',
    ];


   public function transform(Recording $recording) {

      $transformed = [
         'id' => (int) $recording->recordingId,
         'title' => $recording->title,
         'legalStatus' => $recording->legalStatus,
         'copyrightYear' => $recording->copyrightYear,
         'legalStatusKeyValues' => (array) config('avorg.legalStatus'),
      ];

      return $transformed;
   }

   public function includeAgreement(Recording $recording) {
      $agreement = $recording->agreement;
      if ($agreement) {
         return $this->item($agreement, new AgreementIncludeTransformer, 'include');
      }
   }
   public function includeSponsor(Recording $recording) {

      $sponsor = $recording->sponsor;
      if ($sponsor) {
         return $this->item($sponsor, new SponsorIncludeTransformer, 'include');
      }
   }

   public function includeSeries(Recording $recording) {

      $series = $recording->series;
      if ($series) {
         return $this->item($series, new SeriesIncludeTransformer, 'include');
      }
   }

   public function includeConference(Recording $recording) {

      $conference = $recording->conference;

      if ($conference) {
         return $this->item($conference, new ConferenceIncludeTransformer, 'include');
      }
   }
   public function includePresenters(Recording $recording) {

      $presenters = $recording->presenters;
      if ($presenters) {
         return $this->collection($presenters, new PresenterIncludeTransformer, 'include');
      }
   }
}