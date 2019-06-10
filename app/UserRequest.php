<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $table = 'usersRequests';
    protected $primaryKey = 'requestId';

}