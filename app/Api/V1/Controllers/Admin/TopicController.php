<?php
namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Requests\TopicRequest;
use App\Topic;
use App\Transformers\Admin\RecordingTransformer;
use App\Transformers\Admin\TopicTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TopicController extends BaseController
{
   public function all() {

      $this->where = array_merge($this->where, [
         'lang' => config('avorg.default_lang'),
         'parentTopicId' => 0
      ]);

      $topics = Topic::where($this->where)
                  ->orderBy('title', 'asc')
                  ->get();
      
      return $this->response->collection($topics, new TopicTransformer);
   }

   public function create(TopicRequest $request) 
   {
      $topic = new Topic();
      $this->setFields($request, $topic);
      $topic->save();

      return response()->json([
         'message' => 'Topic added.',
         'status_code' => 201
      ], 201);
   }

   public function update(TopicRequest $request) {

      try {
          
         $topic = Topic::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $topic);
         $topic->update();

         return response()->json([
            'message' => 'Topic updated.',
            'status_code' => 200
         ], 200);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Topic {$request->id} not found.");
      }
   }

   public function delete(TopicRequest $request) {
      
      try {

         $topic = Topic::where(['active' => 1])->findOrFail($request->id);
         $topic->active = 0;
         $topic->save();

         return response()->json([
            'message' => "Topic {$request->id} deleted.",
            'status_code' => 201
         ], 201);
         
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Topic {$request->id} not found.");
      }
   }

   public function presentations($topicId) {

      try {
         $topic = Topic::where($this->where)->findOrFail($topicId);
         $recordings = $topic->recordings();

         return $this->response->paginator($recordings->paginate(config('avorg.page_size')), new RecordingTransformer);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Topic $topicId not found.");
      }
   }

   private function setFields(TopicRequest $request, Topic $topic) {

      $topic->title = $request->title;
      $topic->parentTopicId = $request->parentTopicId;
      $topic->lang = $request->lang;
      $topic->hiddenBySelf = $request->hidden;
      $topic->hidden = $request->hidden;
      $topic->active = 1;

      if (!is_null($request->description)) {
         $topic->description = $request->description;
      }

      if (!is_null($request->notes)) {
         $topic->notes = $request->notes;
      }
   }
}