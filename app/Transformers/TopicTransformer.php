<?php

namespace App\Transformers;


use League\Fractal\TransformerAbstract;
use App\Topic;

class TopicTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'children'
    ];

    public function transform(Topic $topic) {
        return [
            'id' => $topic->topicId,
            'parent_id' => $topic->parentTopicId,
            'title' => $topic->title,
           // 'children' => $topic->allChildrenTopics,
        ];
    }

    public function includeChildren(Topic $topic) {

        $topics = $topic->allChildrenTopics;
        return $this->collection($topics, new TopicTransformer);
    }
}