<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model {
   
   protected $table = 'catalogOwners';
   protected $primaryKey = 'ownerId';

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';

   public function agreements() {
      return $this->hasMany('App\Agreement', 'ownerId', 'ownerId')->where([
         'active' => 1
      ]);
   }
}
