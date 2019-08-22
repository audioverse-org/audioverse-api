<?php
namespace App\Api\V1\Controllers\Admin;

use App\License;
use App\Api\V1\Requests\LicenseRequest;
use App\Transformers\Admin\LicenseTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LicenseController extends BaseController 
{
   public function all() {
      
      $license = License::where($this->where)
            ->orderBy('created', 'desc')
            ->paginate(config('avorg.page_size'));

      if ( $license->count() == 0 ) {
         return $this->response->errorNotFound("Licenses not found.");
      }

      return $this->response->paginator($license, new LicenseTransformer);
   }

   public function one($licenseId) {

      try {
         $item = License::where(['active' => 1])->findOrFail($licenseId);
         return $this->response->item($item, new LicenseTransformer);
      } catch( ModelNotFoundException $e) {
         return $this->response->errorNotFound("License {$licenseId} not found.");
      }
   }

   public function create(LicenseRequest $request) 
   {
      try {
         $license = new License();
         $this->setFields($request, $license);
         $license->save();

         return response()->json([
            'message' => 'License added.',
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }

   public function update(LicenseRequest $request) {

      try {
         $license = License::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $license);
         $license->update();

         return response()->json([
            'message' => "License {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("License {$request->id} not found.");
      }
   }

   public function delete(LicenseRequest $request) {

      try {

         $license = License::where(['active' => 1])->findOrFail($request->id);

         if (!$license->agreements()->exists()) {

            $license->active = 0;
            $license->save();

            return response()->json([
               'message' => "License {$request->id} deleted.",
               'status_code' => 201
            ], 201);

         } else {
            return $this->response->errorNotFound("License {$request->id} is referenced in another table thus can not be deleted.");
         }
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("License {$request->id} not found.");
      }
   }

   private function setFields(LicenseRequest $request, License $license) {
      
      $license->title = $request->title;
      $license->summary = $request->summary;
      $license->description = $request->description;
      $license->logo = $request->logo;
      $license->permitsSales = $request->permitsSales;
      $license->lang = $request->lang;

      // At this moment, hidden feature is not being utilized, always shown
      $license->hiddenBySelf = 0;
      $license->hidden = 0;

      $license->notes = $request->notes;
      $license->active = 1;
   }
}