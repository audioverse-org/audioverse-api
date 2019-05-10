<?php
/**
 * Conference Controller
 * @Resource("Conference", uri="/admin/conference")
 */
namespace App\Api\V1\Controllers\Admin;

use App\Conference;
use App\Transformers\World\ConferenceTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConferenceController extends BaseController
{
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
}
