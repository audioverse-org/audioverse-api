<?php
namespace App\Api\V1\Controllers\World;

use App\Sponsor;
use App\Transformers\World\SponsorTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SponsorController extends BaseController {

    protected $model_id = 'sponsorId';

    public function all() {

        $this->where = array_merge($this->where, [
            'lang' => config('avorg.default_lang'),
            'hidden' => 0
        ]);

        $sponsor = Sponsor::where($this->where)
            ->orderBy('title', 'asc')
            ->paginate(config('avorg.page_size'));

        if ( $sponsor->count() == 0 ) {
            return $this->response->errorNotFound("Sponsor not found");
        }

        return $this->response->paginator($sponsor, new SponsorTransformer);
    }

    public function one($sponsor_id) {

        try {
            $item = Sponsor::where($this->where)->findOrFail($sponsor_id);
            return $this->response->item($item, new SponsorTransformer);
        } catch( ModelNotFoundException $e) {
            return $this->response->errorNotFound("Sponsor {$sponsor_id} not found");
        }
    }
}