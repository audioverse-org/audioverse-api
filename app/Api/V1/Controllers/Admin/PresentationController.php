<?php
namespace App\Api\V1\Controllers\Admin;

use App\Recording;
use App\Api\V1\Requests\RecordingRequest;
use App\Api\V1\Requests\LegalUpdateRequest;
use App\Transformers\Admin\RecordingTransformer;
use App\Transformers\Admin\LegalRecordingTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PresentationController extends BaseController {
   
   public function all(Request $request, $id=0) {
      
      $contentTypes = config('avorg.content_type');
      $contentType = $this->getContentType($request->path());
      
      if ($contentType != $contentTypes['presentations']) {
         if ($id > 0) {
            $this->where = array_merge($this->where, [
               'seriesId' => $id,
            ]);
         }
      }

      $this->where = array_merge($this->where, [
         'contentType' => $contentType,
      ]);

      $presentation = Recording::where($this->where)
         ->orderBy('recordingId', 'desc')
         ->paginate(config('avorg.page_size'));

      if ($presentation->count() == 0) {
         return $this->response->errorNotFound("Presentation not found.");
      }

      return $this->response->paginator($presentation, new RecordingTransformer);
   }

   public function one($presentation_id) {

      try {
         $item = Recording::where($this->where)->findOrFail($presentation_id);

         return $this->response->item($item, new RecordingTransformer);

      } catch( ModelNotFoundException $e ) {
         return $this->response->errorNotFound("Presentation {$presentation_id} not found.");
      }
   }

   public function create(RecordingRequest $request) {

      $recording = new Recording();
      $this->setFields($request, $recording);
      $recording->save();

      // Get the newly saved recording id and insert data into pivot tables.
      if (!is_null($request->speakerIds)) {
         foreach ($request->speakerIds as $speakerId)
         {
            // TODO validate if speaker id exists
            $recording->presenters()->attach(
               $speakerId, 
               ['role' => 'speaker', 'active' => 1]
            );
         }
      }

      return response()->json([
         'message' => 'Recording added.',
         'status_code' => 201
      ], 201);
   }

   public function legalAll() {
      
      $presentation = Recording::where($this->where)
            ->where(function($query) {
               $query->where('fileStatus', '<=', 10);
            })
            ->orderBy('recordingId', 'desc')
            ->paginate(config('avorg.page_size'));

      if ( $presentation->count() == 0 ) {
            return $this->response->errorNotFound("Presentations not found.");
      }

      return $this->response->paginator($presentation, new LegalRecordingTransformer);
   }

   public function legalOne($presentation_id) {

      try {
         $item = Recording::where($this->where)
               ->where(function ($query) {
                  $query->where('fileStatus', '<=', 10);
               })->findOrFail($presentation_id);

         return $this->response->item($item, new LegalRecordingTransformer);

      } catch( ModelNotFoundException $e ) {
         return $this->response->errorNotFound("Presentation {$presentation_id} not found");
      }
   }

   public function legalUpdate(LegalUpdateRequest $request) {

      try {

         $recording = Recording::where($this->where)->findOrFail($request->id);
         $recording->agreementId = $request->agreementId;
         $recording->copyrightYear = $request->copyrightYear;
         $recording->notes = $request->notes;
         $recording->legalStatus = $request->legalStatus;
         $recording->update();

         // TODO Find out if there are changes to content approval rules, old logic
         // from admin for reference below:

         /* 
           // what's the next queue this recording goes into?
            $item = $table->find($item->recordingId)->current();
            if ($prevItem->legalStatus >= 10 && $item->legalStatus < 10) { // cleared the Legal queue

                  $triggerEvent = '';
                  if ($item->techStatus >= 20) { // goes to Technical
                     $triggerEvent = 'PendingTechnicalReview';
                  } elseif ($item->contentStatus >= 20) { // goes to Content
                     $triggerEvent = 'PendingContentReview';
                  } elseif ($item->contentStatus < 10) { // goes Public
                     $triggerEvent = 'NewPublicRecording';
                  }

                  if (!empty($triggerEvent)) {
                     $this->_helper->TriggerEventNotices($triggerEvent, 'immediate', '', array('recording' => $item));
                  }
            }
          */

         return response()->json([
            'message' => "Legal review {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal review {$request->id} not found.");
      }
   }

   private function setFields(RecordingRequest $request, Recording $recording) {

      // Automatically determine content type based on request path.
      $recording->contentType = $this->getContentType($request->path());
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
      


      if (!is_null($request->topicIds)) {
         // TODO handle inserting of topics
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
}
