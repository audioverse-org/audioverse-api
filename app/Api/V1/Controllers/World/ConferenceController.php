<?php

namespace App\Api\V1\Controllers\World;

use App\Conference;
use App\Transformers\ConferenceTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConferenceController extends BaseController {

    protected $model_id = 'conferenceId';

    public function all() {

        $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
            'hidden' => 0,
            'contentType' => config('avorg.content_type.presentation')
        ]);

        $presenter = Conference::where($this->where)
            ->orderBy('title', 'asc')
            ->paginate(config('avorg.page_size'));

        if ( $presenter->count() == 0 ) {
            return $this->response->errorNotFound("Conference not found");
        }

        return $this->response->paginator($presenter, new ConferenceTransformer);
    }

    public function one($conference_id) {

        try {
            $item = Conference::where($this->where)->findOrFail($conference_id);
            return $this->response->item($item, new ConferenceTransformer);
        } catch( ModelNotFoundException $e) {
            return $this->response->errorNotFound("Conference {$conference_id} not found");
        }
    }
}