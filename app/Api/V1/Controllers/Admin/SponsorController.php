<?php
namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Requests\SponsorRequest;
use App\Api\V1\Requests\UpdateSponsorRequest;
use App\Sponsor;
use App\Transformers\Admin\SponsorTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @group Sponsor
 *
 * Endpoints for manipulating sponsor catalog.
 */
class SponsorController extends BaseController 
{
   protected $model_id = 'sponsorId';

   /**
    * Get sponsors
    * 
    * Get all sponsors.
    * @authenticated
    * @queryParam lang required string Example: en
    */
   public function all() {

      $sponsor = Sponsor::where($this->where)
         ->orderBy('title', 'asc')
         ->paginate(config('avorg.page_size'));

      if ($sponsor->count() == 0) {
         return $this->response->errorNotFound("Sponsors not found.");
      }

      return $this->response->paginator($sponsor, new SponsorTransformer);
   }

   /**
    * Get one sponsor
    *
    * @authenticated
    * @queryParam lang required string Example: en
    * @urlParam id required id of the presenter. Example: 1
    */
   public function one($sponsor_id) {
      try {
         $item = Sponsor::where($this->where)->findOrFail($sponsor_id);
         return $this->response->item($item, new SponsorTransformer);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Sponsor {$sponsor_id} not found.");
      }
   }
   /**
	 * Create sponsor
	 *
    * @authenticated
    * @queryParam lang required string Example: en
    * @queryParam hiragana required string Example:横浜三育小学校
    * @queryParam title required string Example:This is a title
    * @queryParam summary required string Example:This is a summary
    * @queryParam description required string Example:This is a description
    * @queryParam logo required string Example:logo.jpg
    * @queryParam location required string Example:San Anonio
    * @queryParam website required string Example:http://www.audioverse.org
    * @queryParam publicAddress required string Example:9517 PINE ST
    * @queryParam publicPhone required string Example:423-420-6918
    * @queryParam publicEmail required string Example:john@audioverse.org
    * @queryParam contactName required string Example:John Doe
    * @queryParam contactAddress required string Example:9517 PINE ST
    * @queryParam contactPhone required string Example:423-400-3938
    * @queryParam contactEmail required string Example:jane@audioverse.org
    * @queryParam notes required string Example:This is a note!
    * @queryParam hidden required string Example: 0
    */
   public function create(SponsorRequest $request) {

      $sponsor = new Sponsor();
      $this->setFields($request, $sponsor);
      $sponsor->save();

      return response()->json([
         'message' => 'Sponsor added.',
         'status_code' => 201
      ], 201);
   }

   /**
	 * Update sponsor
	 *
    * @authenticated
    * @queryParam id required string Example: 1
    * @queryParam lang required string Example: en
    * @queryParam hiragana required string Example:横浜三育小学校
    * @queryParam title required string Example:This is a title
    * @queryParam summary required string Example:This is a summary
    * @queryParam description required string Example:This is a description
    * @queryParam logo required string Example:logo.jpg
    * @queryParam location required string Example:San Anonio
    * @queryParam website required string Example:http://www.audioverse.org
    * @queryParam publicAddress required string Example:9517 PINE ST
    * @queryParam publicPhone required string Example:423-420-6918
    * @queryParam publicEmail required string Example:john@audioverse.org
    * @queryParam contactName required string Example:John Doe
    * @queryParam contactAddress required string Example:9517 PINE ST
    * @queryParam contactPhone required string Example:423-400-3938
    * @queryParam contactEmail required string Example:jane@audioverse.org
    * @queryParam notes required string Example:This is a note!
    * @queryParam hidden required string Example: 0
    */
   public function update(SponsorRequest $request) {

      try {
         $sponsor = Sponsor::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $sponsor);
         $sponsor->update();

         return response()->json([
            'message' => 'Sponsor updated.',
            'status_code' => 201
         ], 201);

      } catch( ModelNotFoundException $e ) {
         return $this->response->errorNotFound("Sponsor {$request->id} not found.");
      }
   }

   /**
    * Delete sponsor
    *
    * @authenticated
    * @queryParam id required id of the sponsor. Example: 1
    */
   public function delete(SponsorRequest $request) {
      
      try {
         $sponsor = Sponsor::where(['active' => 1])->findOrFail($request->id);

         if (!$sponsor->conferences()->exists() && !$sponsor->seriess()->exists()) {

            $sponsor->active = 0;
            $sponsor->save();

            return response()->json([
               'message' => "Sponsor {$request->id} deleted.",
               'status_code' => 201
            ], 201);
            
         }
         else {
            return $this->response->errorNotFound("Sponsor {$request->id} is referenced in another table thus can not be deleted.");
         }

      } catch ( ModelNotFoundException $e ) {
         return $this->response->errorNotFound("Sponsor {$request->id} not found.");
      }
   }

   private function setFields(SponsorRequest $request, Sponsor $sponsor) {

      $sponsor->title = $request->title;
      $sponsor->hiragana = $request->hiragana;
      $sponsor->summary = $request->summary;
      $sponsor->description = $request->description;
      $sponsor->logo = $request->logo;
      $sponsor->location = $request->location;
      $sponsor->website = $request->website;
      $sponsor->publicAddress = $request->publicAddress;
      $sponsor->publicPhone = $request->publicPhone;
      $sponsor->publicEmail = $request->publicEmail;
      $sponsor->contactName = $request->contactName;
      $sponsor->contactAddress = $request->contactAddress;
      $sponsor->contactPhone = $request->contactPhone;
      $sponsor->contactEmail = $request->contactEmail;
      $sponsor->lang = $request->lang;
      $sponsor->hiddenBySelf = $request->hidden;
      $sponsor->hidden = $request->hidden;
      $sponsor->notes = $request->notes;
      $sponsor->active = 1;
   }
}