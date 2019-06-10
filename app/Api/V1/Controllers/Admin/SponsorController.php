<?php
namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Requests\SponsorRequest;
use App\Api\V1\Requests\UpdateSponsorRequest;
use App\Sponsor;
use App\Transformers\Admin\SponsorTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SponsorController extends BaseController 
{
   protected $model_id = 'sponsorId';

   public function all() {
      $this->where = array_merge($this->where, [
         'lang' => config('avorg.default_lang'),
         'hidden' => 0
      ]);

      $sponsor = Sponsor::where($this->where)
         ->orderBy('title', 'asc')
         ->paginate(config('avorg.page_size'));

      if ($sponsor->count() == 0) {
         return $this->response->errorNotFound("Sponsors not found.");
      }

      return $this->response->paginator($sponsor, new SponsorTransformer);
   }

   public function one($sponsor_id) {
      try {
         $item = Sponsor::where($this->where)->findOrFail($sponsor_id);
         return $this->response->item($item, new SponsorTransformer);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Sponsor {$sponsor_id} not found.");
      }
   }

   public function create(SponsorRequest $request) {

      $sponsor = new Sponsor();
      $this->setFields($request, $sponsor);
      $sponsor->save();

      return response()->json([
         'message' => 'Sponsor added.',
         'status_code' => 201
      ], 201);
   }

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