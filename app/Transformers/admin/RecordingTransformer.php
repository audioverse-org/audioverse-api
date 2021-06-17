<?php
namespace App\Transformers\Admin;

use App\Recording;
use App\Transformers\World\MediaFileTransformer;
use App\Transformers\World\TopicTransformer;
use League\Fractal\TransformerAbstract;


class RecordingTransformer extends TransformerAbstract {

   protected $defaultIncludes = [
      'audio',
      'video',
      'm3u8web',
      'm3u8ios',
      'file',
      'agreement',
      'sponsor',
      'series',
      'conference',
      'presenters',
      'topics'
   ];

   public function transform(Recording $recording) {

      $transformed = [
         'id' => (int) $recording->recordingId,
         'title' => $recording->title,
         'description' => $recording->description,
         'recording_date' => $recording->recordingDate,
         'publish_date' => $recording->publishDate,
         'duration' => $recording->duration,
         'topics' => $recording->topicNames,
         'hasAudio' => $recording->hasAudio,
         'hasVideo' => $recording->hasVideo,
         'hasHLS' => $recording->hasHLS,
         'editorsPick' => $recording->editorsPick,
         'hasAttachment' => $recording->hasAttachment,
         'hiddenBySelf' => $recording->hiddenBySelf,
         'hiddenByTopics' => $recording->hiddenByTopics,
         'hiddenByPersons' => $recording->hiddenByPersons,
         'hiddenBySeries' => $recording->hiddenBySeries,
         'hiddenByConference' => $recording->hiddenByConference,
         'hiddenBySponsor' => $recording->hiddenBySponsor,
         'hiddenByAgreement' => $recording->hiddenByAgreement,
         'hidden' => $recording->hidden,
         'downloadDisabled' => $recording->downloadDisabled,
         'contentStatus' => $recording->contentStatus,
         'legalStatus' => $recording->legalStatus,
         'techStatus' => $recording->techStatus,
         'fileStatus' => $recording->fileStatus,
         'vendorStatus' => $recording->vendorStatus,
         'notes' => $recording->notes,
         'adGlobal' => $recording->adGlobal,
         'lang' => $recording->lang,
      ];

      if ($recording->siteImageURL != '') {
         $transformed['site_image'] = [
               'file' => $recording->siteImageURL,
               'url' => config('avorg.static_url') .'/'. config("avorg.lang_hash.".config('avorg.default_lang')) . '/gallery/sites/_/',
         ];
      }
      return $transformed;
   }

   public function includeAgreement(Recording $recording) {
      $agreement = $recording->agreement;
      if ( $agreement ) {
         return $this->item($agreement, new AgreementTransformer, 'include');
      }
   }

   public function includeAudio(Recording $recording) {
      return $this->collection($recording->audio, new MediaFileTransformer, 'include');
   }

   public function includeM3u8web(Recording $recording) {
      return $this->collection($recording->m3u8web, new MediaFileTransformer, 'include');
   }

   public function includeM3u8ios(Recording $recording) {
      return $this->collection($recording->m3u8ios, new MediaFileTransformer, 'include');
   }

   public function includeVideo(Recording $recording) {
      return $this->collection($recording->video, new MediaFileTransformer, 'include');
   }

   public function includeFile(Recording $recording) {
      return $this->collection($recording->file, new MediaFileTransformer, 'include');
   }

   public function includeSponsor(Recording $recording) {

      $sponsor = $recording->sponsor;
      if ( $sponsor ) {
         return $this->item($sponsor, new SponsorTransformer, 'include');
      }
   }

   public function includeSeries(Recording $recording) {

      $series = $recording->series;
      if ( $series ) {
         return $this->item($series, new SeriesTransformer, 'include');
      }
   }

   public function includeConference(Recording $recording) {

      $conference = $recording->conference;

      if ( $conference ) {
         return $this->item($conference, new ConferenceTransformer, 'include');
      }
   }

   public function includePresenters(Recording $recording) {

      $presenters = $recording->presenters;
      if ( $presenters ) {
         return $this->collection($presenters, new PresenterTransformer, 'include');
      }
   }

   public function includeTopics(Recording $recording) {

      $topics = $recording->topics;
      return $this->collection($topics, new TopicTransformer, 'include');
   }
}