<?php
namespace App\Api\V1\Controllers\Admin;

use App\Conference;
use App\Series;
use App\Sponsor;
use App\Api\V1\Requests\SeriesRequest;
use App\Traits\SeriesOps;
use App\Transformers\Admin\SeriesTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
/**
 * @group Series
 *
 * Endpoints for manipulating series catalog.
 */
class SeriesController extends BaseController 
{
   protected $model_id = 'seriesId';
   
   use SeriesOps;

   /**
    * Get series
    * 
    * Get all series.
    * @authenticated
    * @queryParam lang required string Example: en
    */
   public function all(Request $request) {

      $series = $this->getSeriess($this->where, $this->contentType);

      if ( $series->count() == 0 ) {
         return $this->response->errorNotFound("Seriess not found");
      }

      return $this->response->paginator($series, new SeriesTransformer);
   }

   /**
    * Get one series
    *
    * @authenticated
    * @urlParam id required id of the series. Example: 1
    */
   public function one($series_id) {

      try {
         $item = Series::where($this->where)->findOrFail($series_id);
         return $this->response->item($item, new SeriesTransformer);
      } catch( ModelNotFoundException $e) {
         return $this->response->errorNotFound("Series {$series_id} not found");
      }
   }

   /**
	 * Create series
	 *
    * @authenticated
    * @queryParam lang required string Example: en
    * @queryParam sponsorId required int
    * @queryParam hiragana required string
    * @queryParam title required string
    * @queryParam summary required string
    * @queryParam description required string
    * @queryParam logo required string
    * @queryParam location required string
    * @queryParam sponsorTitle required string
    * @queryParam sponsorLogo required string
    * @queryParam hidden required string 
    * @queryParam notes required string
    */
   public function create(SeriesRequest $request) 
   {
      try {
         $this->createSeries($request, $this->contentType);
         return response()->json([
            'message' => 'Series added.',
            'status_code' => 201
         ], 201);
      } 
      catch (ModelNotFoundException $e) 
      {
         return $this->response->errorNotFound($e->getMessage());
      }
   }

   /**
	 * Update series
	 *
    * @authenticated
    * @queryParam id required int
    * @queryParam lang required string Example: en
    * @queryParam sponsorId required int
    * @queryParam hiragana required string
    * @queryParam title required string
    * @queryParam summary required string
    * @queryParam description required string
    * @queryParam logo required string
    * @queryParam location required string
    * @queryParam hidden required string 
    * @queryParam notes required string
    */
   public function update(SeriesRequest $request) {

      try {
         $this->updateSeries($request, $this->contentType);
         return response()->json([
            'message' => "Series {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Series {$request->id} not found.");
      }
   }

   /**
    * Delete series
    *
    * @authenticated
    * @queryParam id required id of the presenter. Example: 1
    */
   public function delete(SeriesRequest $request) {

      try {
         $this->deleteSeries($request);
         return response()->json([
            'message' => "Series {$request->id} deleted.",
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }
}