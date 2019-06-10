<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

class BaseTransformer extends TransformerAbstract {

   public function checkModifiedDateIfValid(Model $model) {
      // Return empty string if date is 00000-00-00 
      $modified = $model->modified->format('Y-m-d H:i:s');
      if (!is_numeric(substr($modified, 0, 1))) {
         $modified = "";
      }
      return $modified;
   }
}