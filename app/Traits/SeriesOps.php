<?php
namespace App\Traits;

use App\Conference;
use App\Series;
use App\Sponsor;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait SeriesOps {

   public function getSeriess($where, $contentType) {
      
      $where = array_merge($where, [
         'contentType' => $contentType
      ]);

      $series = Series::where($where)
         ->orderBy('title', 'asc')
         ->paginate(config('avorg.page_size'));
      
      return $series;
   }

   public function createSeries($request, $contentType) {
      try {
         $series = new Series();
         $this->setFields($request, $series, $contentType);
         $series->save();
      } 
      catch (ModelNotFoundException $e) 
      {
         throw new ModelNotFoundException($e->getMessage());
      }
   }

   public function updateSeries($request, $contentType) {
      try {
         $series = Series::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $series, $contentType);
         $series->update();

      } catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException("Series {$request->id} not found.");
      }
   }

   public function deleteSeries($request) {

      try {
         $series = Series::where(['active' => 1])->findOrFail($request->id);

         if ($series->recordings()->exists()) {
            $series->active = 0;
            $series->save();
         }
         else {
            throw new ModelNotFoundException("Series {$request->id} is referenced in another table thus can not be deleted.");
         }
      } catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException("Series {$request->id} not found.");
      }
   }

   private function setFields($request, $series, $contentType) {
      
      try {
         $item = Sponsor::where([
            'active' => 1
         ])->findOrFail($request->sponsorId);
      } 
      catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException("Sponsor $request->sponsorId does not exist.");
      }

      if ($request->conferenceId > 0) {
         try {
            $item = Conference::where([
               'active' => 1
            ])->findOrFail($request->conferenceId);
         } 
         catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Conference $request->conferenceId does not exist.");
         }
      }
      
      $series->contentType = $contentType;
      $series->sponsorId = $request->sponsorId;
      $series->conferenceId = $request->conferenceId;
      $series->hiragana = $request->hiragana;
      $series->title = $request->title;
      $series->summary = $request->summary;
      $series->description = $request->description;
      $series->logo = $request->logo;
      $series->isbn = $request->isbn;
      $series->lang = $request->lang;

      // We are not using these fiels anymore, but we still need to set it to blank
      $series->sponsorTitle = '';
      $series->sponsorLogo = '';
      $series->conferenceTitle = '';
      $series->conferenceLogo = '';

      // When update, hidden calculation will be handled by UpdateHiddenFields event.
      $series->hiddenBySelf = $request->hidden;
      if ($series->seriesId == null) {
         $series->hiddenByConference = 0;
         $series->hiddenBySponsor = 0;
      }
      $series->hidden = $request->hidden;

      $series->notes = $request->notes;
      $series->active = 1;
   }
}