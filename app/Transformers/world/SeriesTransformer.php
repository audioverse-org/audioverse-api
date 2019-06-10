<?php
namespace App\Transformers\World;

use League\Fractal\TransformerAbstract;
use App\Series;

class SeriesTransformer extends TransformerAbstract {

    public function transform(Series $series) {

        return [
            'id' => $series->seriesId,
            'title' => $series->title,
            'summary' => $series->summary,
            'description' => $series->description,
            'logo' => [
                'small' => $series->logoSmall,
                'medium' => $series->logoMedium,
                'large' => $series->logoLarge,
            ],
        ];
    }
}