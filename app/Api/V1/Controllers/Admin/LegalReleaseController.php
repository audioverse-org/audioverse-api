<?php
namespace App\Api\V1\Controllers\Admin;

use App\LegalRelease;
use App\Presenter;
use App\Api\V1\Requests\LegalReleaseRequest;
use App\Transformers\Admin\LegalReleaseTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @group Legal Releases
 *
 * Endpoints for manipulating legal release.
 */
class LegalReleaseController extends BaseController 
{
   public function all() {

      $legalRelease = LegalRelease::where(['active' => 1])
            ->orderBy('created', 'desc')
            ->paginate(config('avorg.page_size'));

      if ( $legalRelease->count() == 0 ) {
         return $this->response->errorNotFound("Legal releases not found.");
      }

      return $this->response->paginator($legalRelease, new LegalReleaseTransformer);
   }

   public function one($legalReleaseId) {

      try {
         $item = LegalRelease::where(['active' => 1])->findOrFail($legalReleaseId);
         return $this->response->item($item, new LegalReleaseTransformer);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal release {$legalReleaseId} not found.");
      }
   }

   public function create(LegalReleaseRequest $request) 
   {
      try {
         $legalRelease = new LegalRelease();
         $this->setFields($request, $legalRelease);
         $legalRelease->save();

         return response()->json([
            'message' => 'Legal release added.',
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }

   public function update(LegalReleaseRequest $request) {

      try {
         $legalRelease = LegalRelease::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $legalRelease);
         $legalRelease->update();

         // If true, it will copy contact info from release to persons table.
         if ($legalRelease->copyContacts > 0 && $legalRelease->presenterId > 0) {

            try {
          
               $presenter = Presenter::where(['active' => 1])->findOrFail($legalRelease->presenterId);
               $presenter->contactAddress = 
                     $legalRelease->address."\n".
                     $legalRelease->address2."\n".
                     $legalRelease->municipality."\n".
                     $legalRelease->province."\n".
                     $legalRelease->postalCode."\n".
                     $legalRelease->country;
               $presenter->contactPhone = $legalRelease->phone;
               $presenter->contactEmail = $legalRelease->email;
               $presenter->save();

            } catch (ModelNotFoundException $e) {
               // presenter not found, log it
               app('log')->warning("Asked to copy contact info for legal release {$request->id}, but presenter {$legalRelease->presenterId} is not found.");
            }
         }

         return response()->json([
            'message' => "Legal release {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal release {$request->id} not found.");
      }
   }

   public function delete(LegalReleaseRequest $request) {

      try {
         $legalRelease = LegalRelease::where(['active' => 1])->findOrFail($request->id);

         if (!$legalRelease->releases()->exists()) {

            $legalRelease->active = 0;
            $legalRelease->save();

            return response()->json([
               'message' => "Legal release {$request->id} deleted.",
               'status_code' => 201
            ], 201);

         } else {
            return $this->response->errorNotFound("Legal release {$request->id} is referenced in another table thus can not be deleted.");
         }
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal release {$request->id} not found.");
      }
   }

   private function setFields(LegalReleaseRequest $request, LegalRelease $legalRelease) {
      
      $legalRelease->conferenceId = $request->conferenceId;
      $legalRelease->termsId = $request->termsId;
      $legalRelease->personId = $request->personId;
      $legalRelease->recordingId = $request->recordingId;
      $legalRelease->agree = $request->agree;
      $legalRelease->firstName = $request->firstName;
      $legalRelease->lastName = $request->lastName;
      $legalRelease->email = $request->email;
      $legalRelease->phone = $request->phone;
      $legalRelease->address = $request->address;
      $legalRelease->address2 = $request->address2;
      $legalRelease->municipality = $request->municipality;
      $legalRelease->province = $request->province;
      $legalRelease->postalCode = $request->postalCode;
      $legalRelease->country = $request->country;
      $legalRelease->comments = $request->comments;
      $legalRelease->active = 1;
   }
}