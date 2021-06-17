<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model {
   
   protected $table = 'catalogConferences';
   protected $primaryKey = 'conferenceId';
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

   public function sponsor() {
      return $this->belongsTo('App\Sponsor', 'sponsorId', 'sponsorId')->where([
         'active' => 1
      ]);
   }

   public function seriess() {
      return $this->hasMany('App\Series', 'conferenceId', 'conferenceId')->where([
         'active' => 1
      ]);
   }

   public function recordings() {
      return $this->hasMany('App\Recording', 'conferenceId', 'conferenceId')->where([
         'active' => 1
      ]);
   }

   public function getLogoSmallAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];
      if ( $this->logo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/conferences/1/86/86/' . $this->logo;
      } elseif ($this->sponsorLogo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/' . $this->sponsorLogo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/default-logo.png';
      }
      return $file_name;
   }

   public function getLogoMediumAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];
      if ( $this->logo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/conferences/1/256/256/' . $this->logo;
      } elseif ($this->sponsorLogo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/' . $this->sponsorLogo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/default-logo.png';
      }
      return $file_name;
   }

   public function getLogoLargeAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];
      if ( $this->logo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/conferences/1/500/500/' . $this->logo;
      } elseif ($this->sponsorLogo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/' . $this->sponsorLogo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/default-logo.png';
      }
      return $file_name;
   }
}
