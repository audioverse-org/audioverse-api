<?php
namespace App\Api\V1\Controllers\Admin;

use App\LegalTerm;
use App\Api\V1\Requests\LegalTermRequest;
use App\Transformers\Admin\LegalTermTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @group Legal Term
 *
 * Endpoints for manipulating legal terms.
 */
class LegalTermController extends BaseController 
{
   /**
    * Get all terms
    * 
    * @authenticated
    * @queryParam lang required string Example: en
    */
   public function all() {

      $legalTerm = LegalTerm::where(['active' => 1])
            ->orderBy('created', 'desc')
            ->paginate(config('avorg.page_size'));

      if ( $legalTerm->count() == 0 ) {
         return $this->response->errorNotFound("Legal terms not found.");
      }

      return $this->response->paginator($legalTerm, new LegalTermTransformer);
   }

   /**
    * Get one term
    * 
    * @authenticated
    * @queryParam id required int
    */
   public function one($legalTermId) {

      try {
         $item = LegalTerm::where(['active' => 1])->findOrFail($legalTermId);
         return $this->response->item($item, new LegalTermTransformer);
      } catch( ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal term {$legalTermId} not found.");
      }
   }

   /**
    * Create term
    * 
    * @authenticated
    * @queryParam label required string
    * @queryParam terms required string
    * @queryParam formType required string
    */
   public function create(LegalTermRequest $request) 
   {
      try {
         $legalTerm = new LegalTerm();
         $this->setFields($request, $legalTerm);
         $legalTerm->save();

         return response()->json([
            'message' => 'Legal term added.',
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }
   /**
    * Update term
    * 
    * @authenticated
    * @queryParam id required string
    * @queryParam label required string
    * @queryParam terms required string
    * @queryParam formType required string
    */
   public function update(LegalTermRequest $request) {

      try {
         $legalTerm = LegalTerm::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $legalTerm);
         $legalTerm->update();

         return response()->json([
            'message' => "Legal term {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal term {$request->id} not found.");
      }
   }
   /**
    * Delete term
    * 
    * @authenticated
    * @queryParam id required string
    */
   public function delete(LegalTermRequest $request) {

      try {
         $legalTerm = LegalTerm::where(['active' => 1])->findOrFail($request->id);

         if (!$legalTerm->releases()->exists()) {

            $legalTerm->active = 0;
            $legalTerm->save();

            return response()->json([
               'message' => "Legal term {$request->id} deleted.",
               'status_code' => 201
            ], 201);

         } else {
            return $this->response->errorNotFound("Legal term {$request->id} is referenced in another table thus can not be deleted.");
         }
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Legal term {$request->id} not found.");
      }
   }

   private function setFields(LegalTermRequest $request, LegalTerm $legalTerm) {
      
      // Verify form type exists
      if (!array_key_exists($request->formType, config('avorg.formType'))) {
         throw new ModelNotFoundException("Legal type $request->formType does not exist.");
      }

      $legalTerm->label = $request->label;
      $legalTerm->terms = $request->terms;
      $legalTerm->formType = $request->formType;

      $legalTerm->active = 1;
   }
}