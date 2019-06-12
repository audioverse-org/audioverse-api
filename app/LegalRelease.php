<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class LegalRelease extends Model {
   
   protected $table = 'legalReleases';

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';

   public function term() {
      return $this->hasOne('App\LegalTerm');
   }

   public function presenter() {
      return $this->hasOne('App\Presenter', 'personId', 'personId');
   }

   public function presentation() {
      return $this->hasOne('App\Recording', 'recordingId', 'recordingId');
   }

   public function conference() {
      return $this->hasOne('App\Conference', 'conferenceId', 'conferenceId');
   }
}
