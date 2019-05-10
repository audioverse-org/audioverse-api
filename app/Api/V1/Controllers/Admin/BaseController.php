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
    public $where = ['active' => 1, 'hidden' => 0];

    public function __construct(Request $request)
    {
        config(['avorg.default_lang' => $request->input('lang', config('avorg.default_lang')) ]);
        // For LengthAwarePaginator
        $this->set_page($request->input('page', 1));
        $this->set_per_page($request->input('per_page', 25));
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
