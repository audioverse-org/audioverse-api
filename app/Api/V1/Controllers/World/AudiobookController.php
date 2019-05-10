<?php

namespace App\Api\V1\Controllers\World;

use App\Series;
use App\Transformers\World\SeriesTransformer;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AudiobookController extends BaseController
{
    protected $content_type;
    protected $model_id = 'seriesId';
    protected $isStory;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->content_type =  config('avorg.content_type.book');
        //Based on url, determine if audiobook or story is requested
        $this->isStory = ( preg_match('/audiobook/', $request->path()) ) ? 0 : 1;
    }

    public function audiobooks()
    {
        $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
            'contentType' => $this->content_type,
            'isStory' => $this->isStory,
        ]);

        $audiobook = Series::where($this->where)
            ->orderBy('title', 'asc')
            ->paginate(config('avorg.page_size'));

        if ( $audiobook->count() == 0 ) {
            return $this->response->errorNotFound("Audiobooks not found");
        }

        return $this->response->paginator($audiobook, new SeriesTransformer);
    }

    public function audiobook($series_id) {

        $this->where = array_merge($this->where, [
            'contentType' => $this->content_type,
            'isStory' => $this->isStory,
        ]);

        try {
            $item = Series::where($this->where)->findOrFail($series_id);
            return $this->response->item($item, new SeriesTransformer);
        } catch( ModelNotFoundException $e) {
            return $this->response->errorNotFound("Audiobook {$series_id} not found");
        }
    }
}