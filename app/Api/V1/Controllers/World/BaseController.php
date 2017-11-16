<?php

namespace App\Api\V1\Controllers\World;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Recording;
use App\Transformers\RecordingTransformer;

class BaseController extends Controller
{
    use Helpers;

    //
    const PERPAGE = 25;

    public $where = ['active' => 1, 'hidden' => 0];

    public function __construct(Request $request)
    {
        config(['avorg.default_lang' => $request->input('lang', config('avorg.default_lang')) ]);
    }

    /**
     * List of presentations
     *
     * Returns a list of presentation. If defined, the list is filtered by contentType, and related table id defined
     * through protected property in the extending class.
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Request("id=123")
     */
    public function presentations($id=0) {

        if ( property_exists($this, 'content_type') ) {
            $this->where = array_merge($this->where, [
                'contentType' => (int) $this->content_type,
            ]);
        } else {
            $this->where = array_merge($this->where, [
                'contentType' => 1,
            ]);
        }

        if ( property_exists($this, 'model_id') ) {
            $this->where = array_merge($this->where, [
                $this->model_id => (int) $id,
            ]);
        }

        $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
            'hasAudio' => 1,
            'legalStatus' => 0,
            'techStatus' => 0,
        ]);

        $presentation = Recording::where($this->where)
            ->where(function($query) {
                $query->orWhere('contentStatus', '=', 0)
                    ->orWhere('contentStatus', '=', 1)
                    ->orWhere('contentStatus', '=', 2);
            })
            ->orderBy('recordingDate', 'desc')
            ->paginate(config('avorg.page_size'));

        if ( $presentation->count() == 0 ) {
            return $this->response->errorNotFound("Presentation not found");
        }

        return $this->response->paginator($presentation, new RecordingTransformer);
    }
}
