<?php

namespace App\Listeners;

use App\Presenter;
use App\Events\UpdateHiddenFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * TODO: This event should queue
 */
class SetHiddenFields // TODO implements ShouldQueue
{
   /**
    * Create the event listener.
      *
      * @return void
      */
   public function __construct()
   {
      // Do nothing
   }

   /**
    * Handle the event.
      *
      * @param  UpdateHiddenFields  $event
      * @return void
      */
   public function handle(UpdateHiddenFields $event)
   {
      switch (get_class($event->model)) {
         case 'App\Agreement':
            $this->updateRecordingsByAgreement($event->model);
            break;
         case 'App\Conference':
            $this->updateRecordingsByConference($event->model);
            $this->updateSeriessByConference($event->model);
            break;
         case 'App\Presenter':
            $this->updateRecordingsByPresenters($event->model);
            break;
         case 'App\Series':
            $this->updateSeries($event->model);
            $this->updateRecordingsBySeries($event->model);
            break;
         case 'App\Sponsor':
            $this->updateRecordingsBySponsor($event->model);
            $this->updateConferenceBySponsor($event->model);
            $this->updateSeriesBySponsor($event->model);
            break;
      }
   }

   private function updateRecordingsByAgreement(\App\Agreement $agreement) {
      $recordings = $agreement->recordings()->getResults();
      if ($recordings->isNotEmpty()) {
         foreach ($recordings as $recording) {
            app('log')->info("SET hiddenByAgreement = {$agreement->hiddenBySelf} for recording {$recording->recordingId}");
            $recording->hiddenByAgreement = $agreement->hiddenBySelf;
            $recording->hidden = 
               $recording->hiddenBySelf + 
               $recording->hiddenByTopics + 
               $recording->hiddenByPersons +
               $recording->hiddenBySeries + 
               $recording->hiddenByConference + 
               $recording->hiddenBySponsor +
               $recording->hiddenByAgreement;
            $recording->save();
         }
      }
   }

   private function updateSeries(\App\Series $series) {

      app('log')->info("SET hidden = {$series->hiddenBySelf} for series {$series->seriesId}");
      $series->hidden = 
         $series->hiddenBySelf + 
         $series->hiddenByConference + 
         $series->hiddenBySponsor;
   }

   private function updateRecordingsBySeries(\App\Series $series) {

      $recordings = $series->recordings()->getResults();
      if ($recordings->isNotEmpty()) {
         foreach ($recordings as $recording) {
            app('log')->info("SET hiddenBySeries = {$series->hiddenBySelf} for recording {$recording->recordingId}");
            $recording->hiddenBySeries = $series->hiddenBySelf;
            $recording->hidden = 
               $recording->hiddenBySelf + 
               $recording->hiddenByTopics + 
               $recording->hiddenByPersons +
               $recording->hiddenBySeries + 
               $recording->hiddenByConference + 
               $recording->hiddenBySponsor +
               $recording->hiddenByAgreement;
            $recording->save();
         }
      }
   }

   private function updateRecordingsByConference(\App\Conference $conference) {

      $recordings = $conference->recordings()->getResults();
      if ($recordings->isNotEmpty()) {
         foreach ($recordings as $recording) {
            app('log')->info("SET hiddenByConference = {$conference->hiddenBySelf} for recording {$recording->recordingId}");
            $recording->hiddenByConference = $conference->hiddenBySelf;
            $recording->hidden = 
               $recording->hiddenBySelf + 
               $recording->hiddenByTopics + 
               $recording->hiddenByPersons +
               $recording->hiddenBySeries + 
               $recording->hiddenByConference + 
               $recording->hiddenBySponsor +
               $recording->hiddenByAgreement;
            $recording->save();
         }
      }
   }

   private function updateSeriessByConference(\App\Conference $conference) {

      $seriess = $conference->seriess()->getResults();
      if ($seriess->isNotEmpty()) {
         foreach ($seriess as $series) {
            app('log')->info("SET hiddenByConference = {$conference->hiddenBySelf}, series {$series->seriesId}, conference {$conference->conferenceId}");
            $series->hiddenByConference = $conference->hiddenBySelf;
            $series->hidden =
               $series->hiddenBySelf + 
               $series->hiddenByConference +
               $series->hiddenBySponsor;
            $series->save();
         }
      }
   }

   private function updateRecordingsByPresenters(\App\Presenter $presenter) {
      
      $recordings = $presenter->recordings()->getResults();
      if ($recordings->isNotEmpty()) {
         foreach ($recordings as $recording) {
            app('log')->info("SET hiddenByPersons = {$presenter->hiddenBySelf}, personId = {$presenter->personId}, recording {$recording->recordingId}");
            $recording->hiddenByPersons = $presenter->hiddenBySelf;
            $recording->hidden = 
               $recording->hiddenBySelf + 
               $recording->hiddenByTopics + 
               $recording->hiddenByPersons +
               $recording->hiddenBySeries + 
               $recording->hiddenByConference + 
               $recording->hiddenBySponsor +
               $recording->hiddenByAgreement;
            $recording->save();
         }
      }
   }

   private function updateRecordingsBySponsor(\App\Sponsor $sponsor) {

      $recordings = $sponsor->recordings()->getResults();
      if ($recordings->isNotEmpty()) {
         foreach ($recordings as $recording) {
            app('log')->info("SET hiddenBySponsor = {$sponsor->hiddenBySelf}, recording {$recording->recordingId}, sponsor {$sponsor->sponsorId}");
            $recording->hiddenBySponsor = $sponsor->hiddenBySelf;
            $recording->hidden = 
               $recording->hiddenBySelf + 
               $recording->hiddenByTopics + 
               $recording->hiddenByPersons +
               $recording->hiddenBySeries + 
               $recording->hiddenByConference + 
               $recording->hiddenBySponsor +
               $recording->hiddenByAgreement;
            $recording->save();
         }
      }
   }

   private function updateConferenceBySponsor(\App\Sponsor $sponsor) {

      $conferences = $sponsor->conferences()->getResults();
      if ($conferences->isNotEmpty()) {
         foreach ($conferences as $conference) {
            app('log')->info("SET hiddenBySponsor = {$sponsor->hiddenBySelf}, conference {$conference->conferenceId}, sponsor {$sponsor->sponsorId}");
            $conference->hiddenBySponsor = $sponsor->hiddenBySelf;
            $conference->hidden = 
               $conference->hiddenBySponsor + 
               $conference->hiddenBySelf;
            $conference->save();
         }
      }
   }

   private function updateSeriesBySponsor(\App\Sponsor $sponsor) {

      $seriess = $sponsor->seriess()->getResults();
      if ($seriess->isNotEmpty()) {
         foreach ($seriess as $series) {
            app('log')->info("SET hiddenBySponsor = {$sponsor->hiddenBySelf}, series {$series->seriesId}, sponsor {$sponsor->sponsorId}");
            $series->hiddenBySponsor = $sponsor->hiddenBySelf;
            $series->hidden =
               $series->hiddenBySelf + 
               $series->hiddenByConference + 
               $series->hiddenBySponsor;
            $series->save();
         }
      }
   }
}
