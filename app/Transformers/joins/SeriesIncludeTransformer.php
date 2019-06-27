<?php
namespace App\Transformers\Joins;

use League\Fractal\TransformerAbstract;
use App\Series;

class SeriesIncludeTransformer extends TransformerAbstract {

    public function transform(Series $series) {

        return [
            'title' => $series->title,
        ];
    }
}