<?php
namespace App\Api\V1\Controllers\Admin;

use App\Recording;
use App\Presenter;
use App\Topic;
use App\Tag;
use App\TagCategory;
use App\Api\V1\Requests\RecordingRequest;
use App\Api\V1\Requests\LegalUpdateRequest;
use App\Transformers\Admin\RecordingTransformer;
use App\Transformers\Admin\LegalRecordingTransformer;
use App\Traits\RecordingOps;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
/**
 * @group Presentation
 *
 * Endpoints for manipulating presentation catalog.
 */
class PresentationController extends BaseController {

   use RecordingOps;

   /**
    * Get presentations
    * 
    * Get all presentation.
    * @authenticated
    * @queryParam lang required string Example: en
    */
   public function all(Request $request, $id=0) {

      $presentation = $this->getRecordings($this->where, $this->contentType, $id);

      if ($presentation->count() == 0) {
         return $this->response->errorNotFound("Presentations not found.");
      }

      return $this->response->paginator($presentation, new RecordingTransformer);
   }

   /**
    * Get one presentation
    *
    * @authenticated
    * @urlParam id required id of the presentation. Example: 1
    * @queryParam lang required string Example: en
    */
   public function one($presentation_id) {

      try {
         $item = Recording::where($this->where)->findOrFail($presentation_id);

         return $this->response->item($item, new RecordingTransformer);

      } catch( ModelNotFoundException $e ) {
         return $this->response->errorNotFound("Presentation {$presentation_id} not found.");
      }
   }
   /**
	 * Create presentation
	 *
    * @authenticated
    * @queryParam sponsorId required int Example:9
    * @queryParam agreementId required int Example:1
    * @queryParam copyrightYear required string Example:2019
    * @queryParam isComplete required int Example:0
    * @queryParam title required string Example:Hello World
    * @queryParam publishDate required string Example:2019-01-01
    * @queryParam lang required string Example:en
    * @queryParam hidden required int Example:0
    * @queryParam downloadDisabled required int Example:0
    * @queryParam conferenceId required int Example:0
    * @queryParam speakerIds[] array peakerIds[0]=333,speakerIds[1]=2...etc
    */
   public function create(RecordingRequest $request) {

      $this->createRecording($request, $this->contentType);
      return response()->json([
         'message' => 'Recording added.',
         'status_code' => 201
      ], 201);
   }

   /**
	 * Update presentation
	 *
    * @authenticated
    * @queryParam id required int Example:9
    * @queryParam sponsorId required int Example:9
    * @queryParam agreementId required int Example:1
    * @queryParam copyrightYear required string Example:2019
    * @queryParam isComplete required int Example:0
    * @queryParam title required string Example:Hello World
    * @queryParam publishDate required string Example:2019-01-01
    * @queryParam lang required string Example:en
    * @queryParam hidden required int Example:0
    * @queryParam downloadDisabled required int Example:0
    * @queryParam conferenceId required int Example:0
    * @queryParam speakerIds[] array peakerIds[0]=333,speakerIds[1]=2...etc
    */
   public function update(RecordingRequest $request) {
      
      try {
         $this->updateRecording($request, $this->contentType);
         return response()->json([
            'message' => "Recording {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Recording {$request->id} not found.");
      }
   }

   /**
    * Delete presentation
    *
    * @authenticated
    * @queryParam id required id of the presentation. Example: 1
    */
   public function delete(RecordingRequest $request) {

      try {
         $this->deleteRecording($request->id);
         return response()->json([
            'message' => "Recording {$request->id} deleted.",
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Recording {$request->id} not found.");
      }
   }

   /**
    * Get presentations for legal
    * 
    * Get all presentation with fileStatus <= 10
    * @authenticated
    * @group Legal
    * @queryParam lang required string Example: en
    */
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

   /**
    * Get one presentation for legal

    * Get one presentation with fileStatus <= 10
    * @authenticated
    * @group Legal
    * @urlParam id required id of the presentation. Example: 1
    */
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

   /**
    * Update legal status for a presentation.
    *
    * @authenticated
    * @group Legal
    * @urlParam id required id of the presentation. Example: 1
    */
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
}
