<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $table = 'catalogMediaFiles';
    protected $primaryKey = 'fileId';
    protected $appends = ['downloadUrl'];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function recording() {

        return $this->hasOne('App\Recording', 'recordingId', 'recordingId');
    }

    public function getDownloadUrlAttribute() {

        $recording = $this->recording;
        $this->downloadUrl = config('avorg.website_url') . '/download/dl/' . $this->attributes['fileId'] . '/' .mb_substr($recording->created, 0, 4).'/'.mb_substr($recording->created, 5, 2).'/'. $this->attributes['filename'];
        return $this->downloadUrl;
    }

    public function setDownloadUrlAttribute($value) {

        $this->downloadUrl = $value;
    }

}
