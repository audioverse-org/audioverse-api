<?php
namespace App\Api\V1\Controllers\Admin;

use App\Topic;
use App\Transformers\Admin\TopicTransformer;

class TopicController extends BaseController
{
   public function all() {

      $this->where = array_merge($this->where, [
         'lang' => config('avorg.default_lang'),
         'parentTopicId' => 0
      ]);

      $topic = Topic::where($this->where)->orderBy('title', 'asc')->paginate(config('avorg.page_size'));

      return $this->response->paginator($topic, new TopicTransformer);
   }

   public function presentation($topic_id) {

      $this->where = array_merge($this->where, [
         'parentTopicId' => $topic_id
      ]);

      $topic = Topic::where($this->where)->orderBy('title', 'asc')->paginate(config('avorg.page_size'));
      return $this->response->paginator($topic, new TopicTransformer);
   }
}