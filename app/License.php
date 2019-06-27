<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model {
   
   protected $table = 'catalogLicenses';
   protected $primaryKey = 'licenseId';

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';

   public function agreements() {
      return $this->hasMany('App\Agreement', 'licenseId', 'licenseId')->where([
         'active' => 1
      ]);
   }
}
