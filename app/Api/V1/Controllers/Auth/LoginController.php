<?php

namespace App\Api\V1\Controllers\Auth;

use App\User;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use Helpers;

    public function login(LoginRequest $request)
    {
        //strip whitespace from the beginning and end of the email
        $email = trim($request->get('email'));
        $password = $request->get('password');

        if ( !Auth::attempt(['email' => $email, 'password' => $password]) ) {
            return $this->response->errorForbidden();
        }

        $user = User::where('email', '=', $email)->first();
        return $this->response->item($user, new UserTransformer);
    }
}
