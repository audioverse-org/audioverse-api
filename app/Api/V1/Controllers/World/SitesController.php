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
use Illuminate\Support\Facades\Cache;
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

            $sortBy = $this->getSortValue($request->input('sortby'));

            // include site name
            $tags = [$site];
            $tagIds = [];

            // get all the tags from get query string parameters
            if ( $request->input('tags') !== null)  {
                $tags = array_merge($tags, $request->input('tags'));
            }
            // get ids of tags for look up on the pivot table
            $tagsCollection = Tag::whereIn('name', $tags)->get();
            // need to know little about our data to build our query, distinguish between "sites" and tags
            foreach($tagsCollection as $tag) {
                if ( $site === $tag->name ) {
                    $tagIds['site'] = [
                        'id' => $tag->id,
                        'name' => $tag->name
                    ];
                } else {
                    $tagIds['tags'][] = [
                        'id' => $tag->id,
                        'name' => $tag->name
                    ];
                }
            }
            // retrieve from cache if not found then store it
            $presentations = Cache::remember($this->getCacheName($site, $sortBy, $tagIds), 60, function() use ($tagIds, $sortBy) {
                // build first filter query
                $siteFilterClause = $this->getWhereClause($tagIds);
                $tagRecordings = TagRecording::select('recordingId')->where(function($query) use ($siteFilterClause) {
                    $query->whereRaw($siteFilterClause);
                })->distinct();

                $tagRecordings =  $tagRecordings->get();
                // empty collections
                $presentations = collect();
                foreach ($tagRecordings as $tagRecording) {
                    $presentations->push($tagRecording->recording()->first());
                }

                $presentations = $presentations->sortBy($sortBy);
                //$presentations = $presentations->sortByDesc($sortBy)

                return $presentations;
            });

            $query_string = urldecode(http_build_query(request()->except('page')));

            // Dingo response paginator expects an object that must implement interface Illuminate\Contracts\Pagination\Paginator
            $presentations = new LengthAwarePaginator(
                $presentations->forPage($this->page,$this->per_page),
                count($presentations),
                $this->per_page,
                $this->page,
                ['path' => url('tags/'.$site).'?'.$query_string]
            );
            return $this->response->paginator($presentations, new RecordingTransformer());

        } catch (ModelNotFoundException $e) {
            throw new ConflictHttpException('Site not found');
        }
    }

    private function getWhereClause($tagIds) {
        // select site category and site id and general tag category id 0
        // "Site" category id is constant
        $siteFilterClause = "((tagCategoryId=".config('avorg.site_tag_category_id')." AND tagId=".$tagIds['site']['id'].") OR (tagCategoryId = 0))";
        if ( isset($tagIds['tags']) ) {
            $siteFilterClause .= ' AND (';
            for($i=0; $i<count($tagIds['tags']); $i++) {
                if ( $i == 0 && (count($tagIds['tags']) > 1)) {
                    $or = ' OR ';
                } else {
                    $or = '';
                }
                $siteFilterClause .= 'tagId='.$tagIds['tags'][$i]['id'].$or;
            }
            $siteFilterClause .= ')';
        }
        return "(".$siteFilterClause.")";
    }

    private function getCacheName($site, $sortBy, $tagIds) {
        $tags = '';
        if ( isset($tagIds['tags']) ) {
            $tags = 'tags.';
            foreach ($tagIds['tags'] as $tag ) {
                $tags .= $tag['id'].'.';
            }
        }
        return "site.$site.page.{$this->page}.perpage.{$this->per_page}.sortby.$sortBy.tags.$tags";
    }

    private function getSortValue($requestedSortValue) {

        // possible values presentations can be sorted by, only asc, mapping to db column
        $sortBy = ["title" => "title", "name" => "speakerNamesGnfFormal", "date" => "recordingDate"];
        // get order criteria
        if ( ($requestedSortValue !== null) && isset($sortBy[$requestedSortValue]) ) {
            $sortValue = $sortBy[$requestedSortValue];
        } else {
            $sortValue = $sortBy["title"];
        }
        return $sortValue;
    }

}