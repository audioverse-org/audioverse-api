<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialAuth extends Model
{
    protected $table = 'usersAuth';
    protected $primaryKey = 'userId';

    const UPDATED_AT = null;
    const CREATED_AT = null;
}