<?php
namespace App;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    use ElasticquentTrait;
    protected $table = 'catalogRecordings';
    protected $primaryKey = 'recordingId';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function audio()
    {
        return $this->hasMany('App\MediaFile', 'recordingId')->where([
            'container' => 'mp3',
            'active' => 1
        ]);
    }
    public function video()
    {
        return $this->hasMany('App\MediaFile', 'recordingId')->where([
            'container' => 'mp4',
            'active' => 1
        ]);
    }

    public function m3u8web()
    {
        return $this->hasMany('App\MediaFile', 'recordingId')->where([
            'container' => 'm3u8_web',
            'active' => 1
        ]);
    }

    public function m3u8ios()
    {
        return $this->hasMany('App\MediaFile', 'recordingId')->where([
            'container' => 'm3u8_ios',
            'active' => 1
        ]);
    }

    public function file()
    {
        return $this->hasMany('App\MediaFile', 'recordingId')->where(function($query) {
            $query->where('container', '!=', 'mp4')
                ->where('container', '!=', 'm3u8_ios')
                ->where('container', '!=', 'm3u8_web')
                ->where('container', '!=', 'mov')
                ->where('container', '!=', 'mp3')
                ->where('active', '=', 1);
        });
    }

    public function topics() {
        return $this->belongsToMany('App\Topic', 'catalogTopicsMap', 'recordingId', 'topicId' );
    }

    public function presenters()
    {
        return $this->belongsToMany('App\Presenter', 'catalogPersonsMap', 'recordingId', 'personId' );
    }

    public function sponsor()
    {
        return $this->hasOne('App\Sponsor', 'sponsorId', 'sponsorId');
    }

    public function conference()
    {
        return $this->hasOne('App\Conference', 'conferenceId', 'conferenceId');
    }

    public function series()
    {
        return $this->hasOne('App\Series', 'seriesId', 'seriesId');
    }

    /**
     * Break up list of topics
     *
     * List of topics are stored separated by ";" character
     * @param  string  $value
     * @return array
     */
    public function getTopicNamesAttribute($value)
    {
        $topics = [];
        try {
            $value = explode(';', $value);
            foreach ( $value as $item ) {
                $trim_value = trim($item, " ");
                if ( $trim_value != "" ) {
                    $topics[] = $trim_value;
                }
            }
        }
        catch( Exception $e ) {

            $trim_value = trim($value, " ");
            if ( $trim_value != "" ) {
                $topics[] = $trim_value;
            }
        }

        return $topics;
    }

}