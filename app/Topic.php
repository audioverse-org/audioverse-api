<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $table = 'catalogTopics';
    protected $primaryKey = 'topicId';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    
    public function childrenTopics() {
        return $this->hasMany('App\Topic', 'parentTopicId', 'topicId');
    }

    public function allChildrenTopics()
    {
        return $this->childrenTopics()->with('allChildrenTopics');
    }
}