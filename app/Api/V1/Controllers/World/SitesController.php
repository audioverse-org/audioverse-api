<?php

/**
 * Tags Resource.
 * @Resource("Tags", uri="/tags")
 */

namespace App\Api\V1\Controllers\World;

use App\Tag;
use App\TagRecording;
use App\Transformers\RecordingTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;


class SitesController extends BaseController
{
    /**
     * Tags
     *
     * Get all presentation based on site and tags
     *
     * @Get("/")
     * @Versions({"v1"})
     */
    public function tags(Request $request, $site) {

        try {

            $tags = [];
            $tagIds = [];
            // get all the tags from get query string parameters
            if ( $request->input('tags') !== null)  {
                $tags = $request->input('tags');
            }
            // include
            $tags[] = $site;

            // get ids of tags for look up on the pivot table
            $tagsCollection = Tag::whereIn('name', $tags)->get();

            foreach($tagsCollection as $tag) {
                $tagIds[] = $tag->id;
            }

            // "Site" category id is constant
            $tagRecordings = TagRecording::where(['tagCategoryId' => config('avorg.site_tag_category_id')])
                                          ->whereIn('tagId',$tagIds)
                                          ->get();
            /*
            DB::enableQueryLog();
            dd(DB::getQueryLog());
            */
            // empty collections
            $presentations = collect();
            foreach ($tagRecordings as $tagRecording) {
                $presentations->push($tagRecording->recording()->first());
            }
            // Dingo response paginator expects an object that must implement interface Illuminate\Contracts\Pagination\Paginator
            $presentations = new LengthAwarePaginator($presentations, count($presentations), $this->per_page);

            return $this->response->paginator($presentations, new RecordingTransformer());

        } catch (ModelNotFoundException $e) {
            throw new ConflictHttpException('Site not found');
        }
    }
}