<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Requests\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use Helpers;

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->reset($user, $password);
            }
        );

        switch ($response) {

            case Password::INVALID_TOKEN:
                return $this->response->error('The token is invalid. Please request another password reset link', 400);
            case Password::INVALID_USER:
                return $this->response->error('No matching user found', 400);
            case Password::INVALID_PASSWORD:
                return $this->response->error('There was an error with the passwords', 400);
            default:
                break;
        }
        return response()->json([
            'message' => 'Reset Complete',
            'status_code' => 201
        ], 201);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  ResetPasswordRequest  $request
     * @return array
     */
    protected function credentials(ResetPasswordRequest $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function reset($user, $password)
    {
        $user->is_crypt_password = 1; // migration purpose, should remove when migration is complete
        $user->password = Hash::make($password);
        $user->save();
    }
}
