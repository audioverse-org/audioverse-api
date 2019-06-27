<?php
namespace App\Api\V1\Controllers\Admin;

use App\Recording;
use App\Api\V1\Requests\LegalUpdateRequest;
use App\Transformers\Admin\RecordingTransformer;
use App\Transformers\Admin\LegalRecordingTransformer;
use App\Transformers\Admin\LegalReviewTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PresentationController extends BaseController
{
   /**
     * List of presentations
     *
     * Returns a list of presentation. If defined, the list is filtered by contentType, and related table id defined
     * through protected property in the extending class.
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Request("id=123")
     */
   public function presentations($id=0) {

      if ( property_exists($this, 'content_type') ) {
            $this->where = array_merge($this->where, [
               'contentType' => (int) $this->content_type,
            ]);
      } else {
            $this->where = array_merge($this->where, [
               'contentType' => 1,
            ]);
      }

      if ( property_exists($this, 'model_id') ) {
            $this->where = array_merge($this->where, [
               $this->model_id => (int) $id,
            ]);
      }

      $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
            'hasAudio' => 1,
            'legalStatus' => 0,
            'techStatus' => 0,
      ]);

      $presentation = Recording::where($this->where)
            ->where(function($query) {
               $query->orWhere('contentStatus', '=', 0)
                  ->orWhere('contentStatus', '=', 1)
                  ->orWhere('contentStatus', '=', 2);
            })
            ->orderBy('recordingDate', 'desc')
            ->paginate(config('avorg.page_size'));

      if ( $presentation->count() == 0 ) {
            return $this->response->errorNotFound("Presentation not found");
      }

      return $this->response->paginator($presentation, new RecordingTransformer);
   }

   /**
    * Presentation
    *
    * Get one presentation
    *
    * @Get("/")
    * @Versions({"v1"})
    */
   public function one($presentation_id) {

      $this->where = array_merge($this->where, [
         'legalStatus' => 0,
         'techStatus' => 0,
      ]);
      try {
         $item = Recording::where($this->where)
               ->where(function ($query) {
                  $query->orWhere('contentStatus', '=', 0)
                     ->orWhere('contentStatus', '=', 1)
                     ->orWhere('contentStatus', '=', 2);
               })->findOrFail($presentation_id);

         return $this->response->item($item, new RecordingTransformer);

      } catch( ModelNotFoundException $e ) {
         return $this->response->errorNotFound("Presentation {$presentation_id} not found");
      }
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

         // TODO Find out if there are changes to content approval rules

         /* LOGIC from admin
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
