<?php

namespace App\Api\V1\Controllers\Auth;

use App\User;
use App\Transformers\World\UserTransformer;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use Helpers;

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return $this->response->errorForbidden();
        }

        $user = User::where('email', '=', $request->get('email'))->first();
        return $this->response->item($user, new UserTransformer);
    }
}
