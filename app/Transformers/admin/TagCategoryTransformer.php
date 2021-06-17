<?php
namespace App\Transformers\Admin;

use App\TagCategory;
use App\Transformers\BaseTransformer;

class TagCategoryTransformer extends BaseTransformer {

    public function transform(TagCategory $tagCategory) {

      return [
         'id' => $tagCategory->id,
         'name' => $tagCategory->name,
         'contentType' => $tagCategory->contentType
      ];
    }
}