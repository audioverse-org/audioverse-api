<?php

namespace App\Api\V1\Controllers\Admin;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
   use Helpers;

   public $per_page;
   public $page;
   public $where = ['active' => 1];

   private $conferenceHidden = 0;
   private $sponsorHidden = 0;

   public function __construct(Request $request) {
      
      // en is default if no language is specified in the parameter
      config(['avorg.default_lang' => $request->input('lang', config('avorg.default_lang')) ]);
      // set language
      $this->where = array_merge($this->where, [
         'lang' => config('avorg.default_lang'),
      ]);

      // For LengthAwarePaginator
      $this->set_page($request->input('page', 1));
      $this->set_per_page($request->input('per_page', 25));
   }

   public function getContentType(String $path) {

   $contentTypes = config('avorg.content_type');

   foreach($contentTypes as $key=>$value) {
      if (preg_match("/$key/", $path)) {
         return $value;
      }
   }
   }

   private function set_page($value) {
      if ( is_numeric($value) && ($value > 0) ) {
         $this->page = $value;
      } else {
         $this->page = 1;
      }
   }
   private function set_per_page($value) {

      if ( is_numeric($value) && ($value > 0) ) {
         $this->per_page = $value;
      } else {
         $this->per_page = 25;
      }
   }
}
