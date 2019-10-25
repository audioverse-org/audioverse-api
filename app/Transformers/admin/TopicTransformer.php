<?php
namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Topic;

class TopicTransformer extends TransformerAbstract
{
   protected $defaultIncludes = [
      'children',
      'numberOfSermons'
   ];

   public function transform(Topic $topic) {
      return [
         'id' => $topic->topicId,
         'parent_id' => $topic->parentTopicId,
         'title' => $topic->title,
      ];
   }

   public function includeNumberOfSermons(Topic $topic) {

      return $this->primitive($topic->recordings()->count());
   }

   public function includeChildren(Topic $topic) {

      $topics = $topic->allChildrenTopics;
      return $this->collection($topics, new TopicTransformer, 'include');
   }
}