<?php
namespace App\Transformers\Admin;

use App\Tag;
use App\Transformers\BaseTransformer;

class TagTransformer extends BaseTransformer {

    public function transform(Tag $tag) {

      return [
         'id' => $tag->id,
         'name' => $tag->name
      ];
    }
}