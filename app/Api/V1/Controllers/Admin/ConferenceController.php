<?php
namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Requests\ConferenceRequest;
use App\Conference;
use App\Sponsor;
use App\Traits\ConferenceOps;
use App\Traits\RecordingOps;
use App\Transformers\Admin\ConferenceTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
/**
 * @group Conference
 *
 * Endpoints for manipulating conference.
 */
class ConferenceController extends BaseController
{
   protected $model_id = 'conferenceId';

   use RecordingOps, ConferenceOps;
   /**
    * Get conferences
    * 
    * Get all conferences.
    * @authenticated
    * @queryParam lang required string Example: en
    */
   public function all(Request $request) {

      $conference = $this->getConferences($this->where, $this->contentType);

      if ( $conference->count() == 0 ) 
      {
         return $this->response->errorNotFound("Conferences not found.");
      }

      return $this->response->paginator($conference, new ConferenceTransformer);
   }

   /**
    * Get one conference
    *
    * @authenticated
    * @urlParam id required id of the presenter. Example: 1
    */
   public function one($conference_id) {

      try {
         $item = Conference::where($this->where)->findOrFail($conference_id);
         return $this->response->item($item, new ConferenceTransformer);
      } catch( ModelNotFoundException $e) {
         return $this->response->errorNotFound("Conference {$conference_id} not found.");
      }
   }

   /**
	 * Create conference
	 *
    * @authenticated
    * @queryParam lang required string Example: en
    * @queryParam sponsorId required int
    * @queryParam hiragana required string
    * @queryParam title required string
    * @queryParam summary required string
    * @queryParam description required string
    * @queryParam logo required string
    * @queryParam location required string
    * @queryParam sponsorTitle required string
    * @queryParam sponsorLogo required string
    * @queryParam hidden required string 
    * @queryParam notes required string
    */
   public function create(ConferenceRequest $request) {

      $conference = new Conference();
      $this->setFields($request, $conference);
      $conference->save();

      return response()->json([
         'message' => 'Conference added.',
         'status_code' => 201
      ], 201);
   }

   /**
	 * Update conference
	 *
    * @authenticated
    * @queryParam id required int
    * @queryParam lang required string Example: en
    * @queryParam sponsorId required int
    * @queryParam hiragana required string
    * @queryParam title required string
    * @queryParam summary required string
    * @queryParam description required string
    * @queryParam logo required string
    * @queryParam location required string
    * @queryParam hidden required string 
    * @queryParam notes required string
    */
   public function update(ConferenceRequest $request) {

      try {
         $conference = Conference::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $conference);
         $conference->update();

         return response()->json([
            'message' => "Conference {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Conference {$request->id} not found.");
      }
   }

   /**
    * Delete conference
    *
    * @authenticated
    * @queryParam id required id of the presenter. Example: 1
    */
   public function delete(ConferenceRequest $request) {

      try {
         $conference = Conference::where(['active' => 1])->findOrFail($request->id);

         if (!$conference->recordings()->exists() && !$conference->seriess()->exists()) {

            $conference->active = 0;
            $conference->save();

            return response()->json([
               'message' => "Conference {$request->id} deleted.",
               'status_code' => 201
            ], 201);
         }
         else {
            return $this->response->errorNotFound("Conference {$request->id} is referenced in another table thus can not be deleted.");
         }

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Conference {$request->id} not found.");
      }
   }

   private function setFields(ConferenceRequest $request, Conference $conference) {

      try {
         $item = Sponsor::where([
            'active' => 1
         ])->findOrFail($request->sponsorId);
      } 
      catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException("Sponsor $request->sponsorId does not exist.");
      }

      $conference->contentType = $request->contentType;
      $conference->sponsorId = $request->sponsorId;
      $conference->hiragana = $request->hiragana;
      $conference->title = $request->title;
      $conference->summary = $request->summary;
      $conference->description = $request->description;
      $conference->logo = $request->logo;
      $conference->location = $request->location;

      // We are not using these fiels anymore, but we still need to set it to blank
      $conference->sponsorTitle = '';
      $conference->sponsorLogo = '';

      $conference->lang = $request->lang;

      // When update, hidden calculation will be handled by UpdateHiddenFields event.
      $conference->hiddenBySelf = $request->hidden;
      // Null means create request, set other hiddens to 0
      if ($conference->conferenceId == null) {
         $conference->hiddenBySponsor = 0;
      }
      
      $conference->hidden = $request->hidden;

      $conference->notes = $request->notes;
      $conference->active = 1;
   }
}
