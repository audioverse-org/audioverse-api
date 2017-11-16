<?php

/**
 * Presentations Resource.
 * @Resource("Presentation", uri="/presentation")
 */

namespace App\Api\V1\Controllers\World;

use App\Recording;
use App\Transformers\RecordingTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PresentationController extends BaseController
{
    /**
     * Presentation
     *
     * Get one presentation
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function one($presentation_id) {

        $this->where = array_merge($this->where, [
            'legalStatus' => 0,
            'techStatus' => 0,
        ]);
        try {
            $item = Recording::where($this->where)
                ->where(function ($query) {
                    $query->orWhere('contentStatus', '=', 0)
                        ->orWhere('contentStatus', '=', 1)
                        ->orWhere('contentStatus', '=', 2);
                })->findOrFail($presentation_id);

            return $this->response->item($item, new RecordingTransformer);
        } catch( ModelNotFoundException $e ) {
            return $this->response->errorNotFound("Presentation {$presentation_id} not found");
        }
    }
}
