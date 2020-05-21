<?php
namespace App\Traits;

use App\Conference;

trait ConferenceOps {

   public function getConferences($where, $contentType) {

      $where = array_merge($where, [
         'contentType' => $contentType
      ]);

      $conference = Conference::where($where)
            ->orderBy('title', 'asc')
            ->paginate(config('avorg.page_size'));

      return $conference;
   }
}