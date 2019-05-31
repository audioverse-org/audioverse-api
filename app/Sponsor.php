<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model {

   protected $table = 'catalogSponsors';
   protected $primaryKey = 'sponsorId';
   protected $appends = [
      'logoSmall',
      'logoMedium',
      'logoLarge'
   ];

   /**
    * The event map for the model.
   *
   * @var array
   */
   protected $events = [
      'updated' => \App\Events\UpdateHiddenFields::class
   ];

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';

   public function conferences() {
      return $this->hasMany('App\Conference', 'sponsorId', 'sponsorId')->where([
         'active' => 1
      ]);
   }

   public function seriess() {
      return $this->hasMany('App\Series', 'sponsorId', 'sponsorId')->where([
         'active' => 1
      ]);
   }

   public function recordings() {
      return $this->hasMany('App\Recording', 'sponsorId', 'sponsorId')->where([
         'active' => 1
      ]);
   }

   public function getLogoSmallAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];

      if ($this->logo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/' . $this->logo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/86/86/default-logo.png';
      }
      return $file_name;
   }

   public function getLogoMediumAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];

      if ($this->logo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/' . $this->logo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/256/256/default-logo.png';
      }
      return $file_name;
   }

   public function getLogoLargeAttribute() {

      $language = config('avorg.lang_hash')[config('avorg.default_lang')];

      if ($this->logo != "" ) {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/' . $this->logo;
      } else {
         $file_name = config('avorg.static_url') . '/' . $language . '/gallery/sponsors/_/500/500/default-logo.png';
      }
      return $file_name;
   }
}
