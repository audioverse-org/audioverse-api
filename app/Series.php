<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model {
   
   protected $table = 'catalogSeriess';
   protected $primaryKey = 'seriesId';
   protected $appends = [
      'logoSmall',
      'logoMedium',
      'logoLarge'
   ];

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';

   /**
   * The event map for the model.
   *
   * @var array
   */
  protected $dispatchesEvents = [
      'updated' => \App\Events\UpdateHiddenFields::class
   ];

   public function recordings() {
      return $this->hasMany('App\Recording', 'seriesId', 'seriesId')->where([
         'active' => 1
      ]);
   }

   public function sponsor() {
      return $this->hasOne('App\Sponsor', 'sponsorId', 'sponsorId')->where([
         'active' => 1
      ]);
   }

   public function conference() {
      return $this->hasOne('App\Conference', 'conferenceId', 'conferenceId')->where([
         'active' => 1
      ]);
   }

   public function getLogoSmallAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];
      // try conference first
      if ( $this->logo != '' ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/series/1/86/86/' . $this->logo;
      } elseif ( $this->conferenceLogo != '' ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/conferences/1/86/86/' . $this->conferenceLogo;
      } elseif ( $this->sponsorLogo != '') {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/' . $this->sponsorLogo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/default-logo.png';
      }
      return $file_name;
   }

   public function getLogoMediumAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];
      // try conference first
      if ( $this->logo != '' ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/series/1/256/256/' . $this->logo;
      } elseif ( $this->conferenceLogo != '' ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/conferences/1/256/256/' . $this->conferenceLogo;
      } elseif ( $this->sponsorLogo != '') {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/' . $this->sponsorLogo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/default-logo.png';
      }
      return $file_name;
   }

   public function getLogoLargeAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];
      // try conference first
      if ( $this->logo != '' ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/series/1/500/500/' . $this->logo;
      } elseif ( $this->conferenceLogo != '' ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/conferences/1/500/500/' . $this->conferenceLogo;
      } elseif ( $this->sponsorLogo != '') {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/' . $this->sponsorLogo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/default-logo.png';
      }
      return $file_name;
   }
}
