<?php
namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Requests\RecordingRequest;
use App\Traits\ConferenceOps;
use App\Traits\RecordingOps;
use App\Traits\SeriesOps;
use App\Transformers\Admin\ConferenceTransformer;
use App\Transformers\Admin\RecordingTransformer;
use App\Transformers\Admin\SeriesTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
/**
 * @group Story
 *
 * Endpoints for manipulating audiobook catalog.
 */
class StoryController extends BaseController 
{
   use RecordingOps, ConferenceOps, SeriesOps;
   /**
    * Get all story books
    * 
    * @authenticated
    * @queryParam lang required string Example: en
    */
   public function allStorybooks(Request $request) {

      $series = $this->getSeriess($this->where, $this->contentType);
      if ( $series->count() == 0 ) {
         return $this->response->errorNotFound("Story books not found.");
      }
      return $this->response->paginator($series, new SeriesTransformer);
   }

   /**
	 * Create story book
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
   public function createStorybook(Request $request) {
      try {
         $this->createSeries($request, $this->contentType);
         return response()->json([
            'message' => 'Story book added.',
            'status_code' => 201
         ], 201);
      } 
      catch (ModelNotFoundException $e) 
      {
         return $this->response->errorNotFound($e->getMessage());
      }
   }

   /**
	 * Update story book
	 *
    * @authenticated
    * @queryParam id required int
    * @queryParam lang required string Example: en
    * @queryParam contentType required int
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
   public function updateStorybook(Request $request) {
      try {
         $this->updateSeries($request, $this->contentType);
         return response()->json([
            'message' => "Story book {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Story {$request->id} not found.");
      }
   }

   /**
    * Delete story book
    *
    * @authenticated
    * @queryParam id required id of the presenter. Example: 1
    */
   public function deleteStorybook(Request $request) {
      try {
         $this->deleteSeries($request);
         return response()->json([
            'message' => "Story book {$request->id} deleted.",
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound($e->getMessage());
      }
   }

   /**
    * Get all story chapters
    * 
    * Get chapters for all story books.
    * @authenticated
    * @queryParam lang required string Example: en
    */
    public function allChapters(Request $request, $id=0) {

      $presentation = $this->getRecordings($this->where, $this->contentType, $id);

      if ($presentation->count() == 0) {
         return $this->response->errorNotFound("Chapters not found.");
      }

      return $this->response->paginator($presentation, new RecordingTransformer);
   }

   /**
    * Get chapters
    *
    * Get all chapters for a story book.
    * @authenticated
    * @queryParam lang required string Example: en
    * @queryParam id required int
    */
   public function chapters(Request $request, $id = 0) {

      $presentation = $this->getRecordings($this->where, $this->contentType, $id);

      if ($presentation->count() == 0) {
         return $this->response->errorNotFound("Chapters not found.");
      }

      return $this->response->paginator($presentation, new RecordingTransformer);
   }

   /**
    * Get story seriess
    * 
    * Get all seriess for an story.
    * @authenticated
    * @queryParam lang required string Example: en
    */
    public function seriess(Request $request) {

      $conference = $this->getConferences($this->where, $this->contentType);

      if ( $conference->count() == 0 ) 
      {
         return $this->response->errorNotFound("Seriess not found.");
      }

      return $this->response->paginator($conference, new ConferenceTransformer);
   }

   /**
	 * Add chapter
	 *
    * Add a chapter for an story.
    * @authenticated
    * @queryParam sponsorId required int Example:9
    * @queryParam agreementId required int Example:1
    * @queryParam copyrightYear required string Example:2019
    * @queryParam isComplete required int Example:0
    * @queryParam title required string Example:Hello World
    * @queryParam publishDate required string Example:2019-01-01
    * @queryParam lang required string Example:en
    * @queryParam hidden required int Example:0
    * @queryParam downloadDisabled required int Example:0
    * @queryParam conferenceId required int Example:0
    * @queryParam speakerIds[] array peakerIds[0]=333,speakerIds[1]=2...etc
    */
    public function createChapter(RecordingRequest $request) {

      $this->createRecording($request, $this->contentType);
      return response()->json([
         'message' => 'Chapter added.',
         'status_code' => 201
      ], 201);
   }

   /**
	 * Update chapter
	 *
    * Update a chapter for an story.
    * @authenticated
    * @queryParam id required int Example:9
    * @queryParam sponsorId required int Example:9
    * @queryParam agreementId required int Example:1
    * @queryParam copyrightYear required string Example:2019
    * @queryParam isComplete required int Example:0
    * @queryParam title required string Example:Hello World
    * @queryParam publishDate required string Example:2019-01-01
    * @queryParam lang required string Example:en
    * @queryParam hidden required int Example:0
    * @queryParam downloadDisabled required int Example:0
    * @queryParam conferenceId required int Example:0
    * @queryParam speakerIds[] array peakerIds[0]=333,speakerIds[1]=2...etc
    */
    public function updateChapter(RecordingRequest $request) {
      
      try {
         $this->updateRecording($request, $this->contentType);
         return response()->json([
            'message' => "Chapter {$request->id} updated.",
            'status_code' => 201
         ], 201);

      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Chapter {$request->id} not found.");
      }
   }

   /**
    * Delete a chapter
    *
    * Delete a chapter from an story.
    * @authenticated
    * @queryParam id required id of the recording. Example: 1
    */
    public function deleteChapter(RecordingRequest $request) {

      try {
         $this->deleteRecording($request->id);
         return response()->json([
            'message' => "Recording {$request->id} deleted.",
            'status_code' => 201
         ], 201);
      } catch (ModelNotFoundException $e) {
         return $this->response->errorNotFound("Recording {$request->id} not found.");
      }
   }
}