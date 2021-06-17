<?php

namespace App\Api\V1\Controllers\Auth;

use App\User;
use App\Transformers\World\UserTransformer;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\LoginRequest;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
/**
 * @group User Management
 *
 * Endpoints for manipulating users.
 */
class LoginController extends Controller
{
    use Helpers;
   /**
    * Login user
    * 
    * @authenticated
    * @queryParam email required string
    * @queryParam password required string
    */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::guard('web')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return $this->response->errorForbidden();
        }

        $user = User::where('email', '=', $request->get('email'))->first();
        return $this->response->item($user, new UserTransformer);
    }
}
