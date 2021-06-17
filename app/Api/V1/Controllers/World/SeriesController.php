<?php

namespace App\Api\V1\Controllers\World;

use App\Series;
use App\Transformers\World\SeriesTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SeriesController extends BaseController {

    protected $model_id = 'seriesId';

    public function all() {

        $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
            'hidden' => 0,
            'contentType' => config('avorg.content_type.presentation')
        ]);

        $series = Series::where($this->where)
            ->orderBy('title', 'asc')
            ->paginate(config('avorg.page_size'));

        if ( $series->count() == 0 ) {
            return $this->response->errorNotFound("Series not found");
        }

        return $this->response->paginator($series, new SeriesTransformer);
    }

    public function one($series_id) {

        try {
            $item = Series::where($this->where)->findOrFail($series_id);
            return $this->response->item($item, new SeriesTransformer);
        } catch( ModelNotFoundException $e) {
            return $this->response->errorNotFound("Series {$series_id} not found");
        }
    }
}