<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class LegalTerm extends Model {
   
   protected $table = 'legalTerms';

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';

   public function releases() {
      return $this->hasMany('App\LegalRelease', 'termsId', 'id')->where([
         'active' => 1
      ]);
   }
}
