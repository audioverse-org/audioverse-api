<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class TagRecording extends Model
{
    protected $table = 'tagsRecordings';
    protected $primaryKey = null;

    public function recording() {
        //return $this->hasMany('App\Recording', 'recordingId', 'recordingId');
        return $this->hasOne('App\Recording', 'recordingId', 'recordingId');
    }
}