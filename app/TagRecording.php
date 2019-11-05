<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class TagRecording extends Model
{
    protected $table = 'tagsRecordings';
    protected $primaryKey = null;

    const CREATED_AT = 'created';
    const UPDATED_AT = null;

    public function recording() {
        return $this->hasOne('App\Recording', 'recordingId', 'recordingId');
    }
}