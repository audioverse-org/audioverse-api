<?php

namespace App\Api\V1\Controllers\World;

use App\Presenter;
use App\Transformers\World\PresenterTransformer;
use App\Transformers\World\RecordingTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PresenterController extends BaseController {

    public function all() {

        $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
        ]);
        $presenter = Presenter::where($this->where)->paginate(config('avorg.page_size'));

        if ( $presenter->count() == 0 ) {
            return $this->response->errorNotFound("Presenters not found");
        }

        return $this->response->paginator($presenter, new PresenterTransformer);
    }

    public function one($presenter_id) {

        try {
            $item = Presenter::where($this->where)->findOrFail($presenter_id);
            return $this->response->item($item, new PresenterTransformer);
        } catch( ModelNotFoundException $e) {
            return $this->response->errorNotFound("Presenter {$presenter_id} not found");
        }
    }

    public function presentation($presenter_id) {

        try {
            $presenter = Presenter::where($this->where)->findOrFail($presenter_id);
            return $this->response->paginator(
                $presenter->recordings()->paginate(config('avorg.page_size')),
                new RecordingTransformer
            );
        } catch( ModelNotFoundException $e) {
            return $this->response->errorNotFound("Presenter {$presenter_id} not found");
        }
    }
}