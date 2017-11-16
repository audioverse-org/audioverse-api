<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presenter extends Model
{
    protected $table = 'catalogPersons';
    protected $primaryKey = 'personId';
    protected $appends = [
        'logoSmall',
        'logoMedium',
        'logoLarge'
    ];
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function recordings() {
        return $this->belongsToMany('App\Recording',  'catalogPersonsMap', 'personId', 'recordingId' );
    }

    public function getLogoSmallAttribute() {

        $language = config('avorg.lang_hash')[config('avorg.default_lang')];
        if ( $this->photo != "" ) {
            $file_name = config('avorg.static_url') . '/' . $language . '/gallery/persons/_/86/86/' . $this->photo;
        } else {
            $file_name = $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/default-logo.png';
        }
        return $file_name;
    }

    public function getLogoMediumAttribute() {

        $language = config('avorg.lang_hash')[config('avorg.default_lang')];
        if ( $this->photo != "" ) {
            $file_name = config('avorg.static_url') . '/' . $language . '/gallery/persons/_/256/256/' . $this->photo;
        } else {
            $file_name = $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/default-logo.png';
        }
        return $file_name;
    }

    public function getLogoLargeAttribute() {

        $language = config('avorg.lang_hash')[config('avorg.default_lang')];
        if ( $this->photo != "" ) {
            $file_name = config('avorg.static_url') . '/' . $language . '/gallery/persons/_/500/500/' . $this->photo;
        } else {
            $file_name = $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/default-logo.png';
        }
        return $file_name;
    }

    public function getSummaryAttribute($value) {
        return preg_replace("/[\r\n]+/", "", strip_tags(html_entity_decode($value)));
    }
    public function getDescriptionAttribute($value) {
        return preg_replace("/[\r\n]+/", "", strip_tags(html_entity_decode($value)));
    }
}
