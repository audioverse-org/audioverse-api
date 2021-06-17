<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

   protected $table = 'tags';

   const CREATED_AT = null;
   const UPDATED_AT = null;

   public function recordings() {
      return $this->hasMany('App\TagRecording', 'tagId', 'id');
  }
}