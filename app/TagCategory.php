<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class TagCategory extends Model
{
    protected $table = 'tagsCategory';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    public function recordings() {
      return $this->hasMany('App\TagRecording', 'tagCategoryId');
   }
}