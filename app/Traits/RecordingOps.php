<?php
namespace App\Traits;

use App\Conference;
use App\Presenter;
use App\Recording;
use App\Series;
use App\Tag;
use App\TagCategory;
use App\Topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait RecordingOps {

   public function getRecordings($where, $contentType, $seriesId = 0) {

      $contentTypes = config('avorg.content_type');
      if ($contentType != $contentTypes['presentations']) {
         if ($seriesId > 0) {
            $where = array_merge($where, [
               'seriesId' => $seriesId,
            ]);
         }
      }

      $where = array_merge($where, [
         'contentType' => $contentType,
      ]);

      $presentation = Recording::where($where)
         ->orderBy('recordingId', 'desc')
         ->paginate(config('avorg.page_size'));

      return $presentation;
   }

   public function createRecording($request, $contentType) {

      $recording = new Recording();
      $this->setRecordingFields($request, $recording, $contentType);
      $recording->save();

      // Insert person id into pivot tables.
      if (!is_null($request->speakerIds)) {
         foreach ($request->speakerIds as $speakerId)
         {
            if (Presenter::where(['personId' => $speakerId])->exists())
            {
               $recording->presenters()->attach(
                  $speakerId, 
                  ['role' => 'speaker', 'active' => 1]
               );
            }
            else
            {
               app('log')->warning("Recording id {$recording->recordingId} created, but personId $speakerId does not exists. Failed to insert into catalogPersonsMap table.");
            }
         }
      }
      
      // Insert topic id into pivot tables.
      if (!is_null($request->topicIds)) {
         foreach ($request->topicIds as $topicId)
         {
            if (Topic::where(['topicId' => $topicId])->exists())
            {
               $recording->topics()->attach($topicId, ['active' => 1]);
            }
            else
            {
               app('log')->warning("Recording id {$recording->recordingId} created, but topicId $topicId does not exists. Failed to insert into catalogTopicsMap table.");
            }
         }
      }

      // Insert tags into pivot tables.
      if (!is_null($request->tags)) {
         $this->saveTags($request, $recording, false);
      }
   }

   public function updateRecording($request, $contentType) {
      try {

         $recording = Recording::where(['active' => 1])->findOrFail($request->id);
         $this->setRecordingFields($request, $recording, $contentType);
         $recording->update();

         // Update persons pivot table
         if (!is_null($request->speakerIds)) {
            $validSpeakers = array();
            foreach ($request->speakerIds as $speakerId)
            {
               if (Presenter::where(['personId' => $speakerId])->exists())
               {
                  $validSpeakers[$speakerId] = ['role' => 'speaker', 'active' => 1];
               }
               else
               {
                  app('log')->warning("Recording id {$recording->recordingId} updated, but personId $speakerId does not exists. Failed to update catalogPersonsMap table.");
               }
            }

            if (count($validSpeakers) > 0) {
               $recording->presenters()->sync($validSpeakers);
            }
         }

         // Update topics pivot table
         if (!is_null($request->topicIds)) {

            $validTopics = array();
            foreach ($request->topicIds as $topicId)
            {
               if (Topic::where(['topicId' => $topicId])->exists())
               {
                  $validTopics[$topicId] = ['active' => 1];
               }
               else
               {
                  app('log')->warning("Recording id {$recording->recordingId} updated, but topicId $topicId does not exists. Failed to update catalogTopicsMap table.");
               }
            }

            if (count($validTopics) > 0) {
               $recording->topics()->sync($validTopics);
            }
         }

         // Insert tags into pivot tables.
         if (!is_null($request->tags)) {
            $this->saveTags($request, $recording, true);
         }

      } catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException;
      }
   }

   public function deleteRecording($id) {
      try {
         $recording = Recording::where(['active' => 1])->findOrFail($id);
         $recording->active = 0;
         $recording->save();
         // Clean up pivot tables
         $recording->presenters()->detach();
         $recording->topics()->detach();
         $recording->tags()->detach();
      } catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException;
      }
   }

   private function setRecordingFields($request, $recording, $contentType) {

      // Automatically determine content type based on request path.
      $recording->contentType = $contentType;
      $recording->sponsorId = $request->sponsorId;

      if (!is_null($request->conferenceId)) {
         $recording->conferenceId = $request->conferenceId;
      }

      if (!is_null($request->seriesId)) {
         $recording->seriesId = $request->seriesId;
      }

      $recording->title = $request->title;
      $recording->publishDate = $request->publishDate;

      if (!is_null($request->recordingDate)) {
         $recording->recordingDate = $request->recordingDate;
      }

      $recording->agreementId = $request->agreementId;

      if (!is_null($request->copyrightYear)) {
         $recording->copyrightYear = $request->copyrightYear;
      }

      $recording->isComplete = $request->isComplete;

      if (!is_null($request->description)) {
         $recording->description = $request->description;
      }

      if (!is_null($request->siteImageURL)) {
         // TODO uploading of images
         $recording->siteImageURL = $request->siteImageURL;
      }

      $recording->lang = $request->lang;

      // TODO Evoke hidden calculation
      $recording->hiddenBySelf = $request->hidden;

      $recording->downloadDisabled = $request->downloadDisabled;

      // TODO Figure out rules for status 
      /*
      $recording->contentStatus = $request->contentStatus;
      $recording->legalStatus = $request->legalStatus;
      $recording->techStatus = $request->techStatus;
      $recording->fileStatus = $request->fileStatus;
      $recording->vendorStatus = $request->vendorStatus;
      */

      if (!is_null($request->notes)) {
         $recording->notes = $request->notes;
      }

      $recording->active = 1;
   }

   private function saveTags($request, $recording, $update) {

      foreach ($request->tags as $tagCategoryId => $tagNames)
      {
         // Check if tag category exists
         if (TagCategory::where(['id' => $tagCategoryId])->exists()) 
         {
            if (is_array($tagNames)) 
            {
               $idsToSync = [];
               foreach ($tagNames as $name)
               {
                  // Check if tags exists
                  $tag = Tag::where(['name' => $name])->first();
                  $tagId = 0;
                  if (!is_null($tag)) 
                  {
                     $tagId = $tag->id;
                  } 
                  else 
                  {
                     $tag = new Tag();
                     $tag->name = $name;
                     $tag->lang = config('avorg.default_lang');
                     $tag->save();
                     $tagId = $tag->id;
                  }
                  // Save into pivot table.
                  $idsToSync[$tagId] = ['tagCategoryId' => $tagCategoryId];
               }

               if (count($idsToSync) > 0)
               {
                  if ($update) 
                  {
                     $recording->tags()->sync($idsToSync);
                  }
                  else
                  {
                     $recording->tags()->attach($idsToSync);
                  }
               }
            }
            else
            {
               app('log')->warning("Tag category $tagCategoryId exists, but tags is not an array.");
            }
         }
         else
         {
            app('log')->warning("Tag category $tagCategoryId does not exists. Failed to insert tags into tagsRecordings table.");
         }
      }
   }
}