<?php

namespace App\Api\V1\Controllers\World;

use App\Recording;

class MusicController extends BaseController
{
    public function latest() {
        $temp = Recording::search('Jesus');
        print_r($temp);
    }

    public function albums() {

    }

    public function books() {

    }

    public function mood() {

    }

    public function genre() {

    }
}