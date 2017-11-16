<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{

    protected $table = 'usersNewsletter';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

}