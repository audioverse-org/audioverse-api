<?php
namespace App\Api\V1\Controllers\Admin;

use App\Tag;
use App\Api\V1\Requests\TagRequest;
use App\Transformers\Admin\TagTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagController extends BaseController 
{
   public function all() {

      $tag = Tag::where([
         'lang' => config('avorg.default_lang')
      ])->orderBy('name', 'asc')
         ->paginate(config('avorg.page_size'));

      if ( $tag->count() == 0 ) {
         return $this->response->errorNotFound("Tags not found.");
      }

      return $this->response->paginator($tag, new TagTransformer);
   }

   public function create(TagRequest $request) 
   {
      try {

         if ($request->name === NULL)
         {
            return $this->response->errorNotFound('Either "name" or "name[]" is required.');
         }

         elseif (is_array($request->name)) {
            // Process array of tags.
            foreach ($request->name as $name) {
               // Insert only if unique.
               if (!Tag::where(['name'=> $name, 'lang'=> config('avorg.default_lang')])->exists()) {
                  $tag = new Tag();
                  $tag->name = $name;
                  $tag->lang = config('avorg.default_lang');
                  $tag->save();
               }
               else
               {
                  app('log')->info("Tag $name already exists, skipping.");
               }
            }
         } else {
            if (!Tag::where(['name'=> $request->name, 'lang'=> config('avorg.default_lang')])->exists()) {
               $tag = new Tag();
               $tag->name = $request->name;
               $tag->lang = config('avorg.default_lang');
               $tag->save();
            }
            else {
               app('log')->info("Tag $request->name already exists, skipping.");
            }
         }

         return response()->json([
            'message' => 'Tag added.',
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }

   public function update(TagRequest $request) {

      try {
         
         $tag = Tag::findOrFail($request->id);
         $tag->name = $request->name;
         $tag->update();

         return response()->json([
            'message' => "Tag {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Tag {$request->id} not found.");
      }
   }

   public function delete(TagRequest $request) {
      
      try {

         $tag = Tag::findOrFail($request->id);

         if (!$tag->recordings()->exists())
         {
            $tag->delete();
            return response()->json([
               'message' => "Tag {$request->id} deleted.",
               'status_code' => 201
            ], 201);

         } else {
            return $this->response->errorNotFound("Tag {$request->id} is referenced in another table thus can not be deleted.");
         }

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Tag {$request->id} not found.");
      }
   }
}