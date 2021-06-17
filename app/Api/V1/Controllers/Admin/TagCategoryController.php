<?php
namespace App\Api\V1\Controllers\Admin;

use App\TagCategory;
use App\Api\V1\Requests\TagCategoryRequest;
use App\Transformers\Admin\TagCategoryTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * @group Tag Category
 *
 * Endpoints for manipulating tags.
 */
class TagCategoryController extends BaseController 
{
   /**
    * Get all tag categories
    * 
    * @authenticated
    */
   public function all() {

      $tagCategory = TagCategory::where([
         'lang' => config('avorg.default_lang')
      ])->orderBy('name', 'asc')
         ->paginate(config('avorg.page_size'));

      if ( $tagCategory->count() == 0 ) {
         return $this->response->errorNotFound("Tag categories not found.");
      }

      return $this->response->paginator($tagCategory, new TagCategoryTransformer);
   }
   /**
    * Create tag categories
    * 
    * @authenticated
    * @queryParam name required string
    * @queryParam contentType required string
    * @queryParam lang required string Example: en
    */
   public function create(TagCategoryRequest $request) 
   {
      try {
         $tagCategory = new TagCategory();
         $tagCategory->name = $request->name;
         $tagCategory->lang = config('avorg.default_lang');
         $tagCategory->contentType = $request->contentType;
         $tagCategory->save();

         return response()->json([
            'message' => 'Tag category added.',
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }
   /**
    * Update tag category
    * 
    * @authenticated
    * @queryParam name required string
    * @queryParam contentType required string
    * @queryParam id required int
    * @queryParam lang required string Example: en
    */
   public function update(TagCategoryRequest $request) {

      try {
         $tagCategory = TagCategory::findOrFail($request->id);
         $tagCategory->name = $request->name;
         $tagCategory->contentType = $request->contentType;
         $tagCategory->update();

         return response()->json([
            'message' => "Tag category {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Tag category {$request->id} not found.");
      }
   }
   /**
    * Delete tag category
    * 
    * @authenticated
    * @queryParam id required int
    */
   public function delete(TagCategoryRequest $request) {
      
      try {
         $tagCategory = TagCategory::findOrFail($request->id);
         // To prevent orphans, prevent deletion of tag category if being used.
         if (!$tagCategory->recordings()->exists()) {
            $tagCategory->delete();
            return response()->json([
               'message' => "Tag category {$request->id} deleted.",
               'status_code' => 201
            ], 201);
         }
         else {
            return $this->response->errorNotFound("Tag category {$request->id} is referenced in a junction table thus can not be deleted.");
         }
      
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Tag category {$request->id} not found.");
      }
   }
}