<?php
namespace App\Api\V1\Controllers\Admin;

use App\Conference;
use App\Series;
use App\Sponsor;
use App\Api\V1\Requests\SeriesRequest;
use App\Transformers\Admin\SeriesTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SeriesController extends BaseController 
{
   protected $model_id = 'seriesId';

   public function all(Request $request) {

      $this->where = array_merge($this->where, [
         'contentType' => $this->getContentType($request->path())
      ]);

      $series = Series::where($this->where)
         ->orderBy('title', 'asc')
         ->paginate(config('avorg.page_size'));

      if ( $series->count() == 0 ) {
         return $this->response->errorNotFound("Seriess not found");
      }

      return $this->response->paginator($series, new SeriesTransformer);
   }

   public function one($series_id) {

      try {
         $item = Series::where($this->where)->findOrFail($series_id);
         return $this->response->item($item, new SeriesTransformer);
      } catch( ModelNotFoundException $e) {
         return $this->response->errorNotFound("Series {$series_id} not found");
      }
   }

   public function create(SeriesRequest $request) 
   {
      try {
         $series = new Series();
         $this->setFields($request, $series);
         $series->save();

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

   public function update(SeriesRequest $request) {

      try {
         $series = Series::where(['active' => 1])->findOrFail($request->id);
         $this->setFields($request, $series);
         $series->update();

         return response()->json([
            'message' => "Series {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Series {$request->id} not found.");
      }
   }

   public function delete(SeriesRequest $request) {

      try {
         $series = Series::where(['active' => 1])->findOrFail($request->id);

         if ($series->recordings()->exists()) {

            $series->active = 0;
            $series->save();

            return response()->json([
               'message' => "Series {$request->id} deleted.",
               'status_code' => 201
            ], 201);
         }
      
         else {
            return $this->response->errorNotFound("Series {$request->id} is referenced in another table thus can not be deleted.");
         }
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Series {$request->id} not found.");
      }
   }
   private function setFields(SeriesRequest $request, Series $series) {
      
      try {
         $item = Sponsor::where([
            'active' => 1
         ])->findOrFail($request->sponsorId);
      } 
      catch (ModelNotFoundException $e) {
         throw new ModelNotFoundException("Sponsor $request->sponsorId does not exist.");
      }

      if ($request->conferenceId > 0) {
         try {
            $item = Conference::where([
               'active' => 1
            ])->findOrFail($request->conferenceId);
         } 
         catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException("Conference $request->conferenceId does not exist.");
         }
      }
      $series->contentType = $request->contentType;
      $series->sponsorId = $request->sponsorId;
      $series->conferenceId = $request->conferenceId;
      $series->hiragana = $request->hiragana;
      $series->title = $request->title;
      $series->summary = $request->summary;
      $series->description = $request->description;
      $series->logo = $request->logo;
      $series->isbn = $request->isbn;
      $series->lang = $request->lang;

      // We are not using these fiels anymore, but we still need to set it to blank
      $series->sponsorTitle = '';
      $series->sponsorLogo = '';
      $series->conferenceTitle = '';
      $series->conferenceLogo = '';

      // When update, hidden calculation will be handled by UpdateHiddenFields event.
      $series->hiddenBySelf = $request->hidden;
      if ($series->seriesId == null) {
         $series->hiddenByConference = 0;
         $series->hiddenBySponsor = 0;
      }
      $series->hidden = $request->hidden;

      $series->notes = $request->notes;
      $series->active = 1;
   }
}