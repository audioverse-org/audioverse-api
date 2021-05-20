<?php

namespace App\Api\V1\Controllers\Auth;

use App\Events\SignUp;
use App\User;
use App\UserPreferences;
use App\UserRequest;
use App\Notifications\VerifyEmail;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @group User Management
 *
 * Endpoints for manipulating users.
 */
class SignUpController extends Controller
{
    use Helpers;

    /**
     * Sign up user
     * 
     * It also send email verification message as long as the background job is running.
     *
     * @authenticated
     * @queryParam email required string
     * @queryParam password required string
     */
    public function signUp(SignUpRequest $request) {

        $user = new User($request->all());

        // check if the user exists
        if ($existing_user = User::where(['email' => $user->email, 'active' => 1])->first()) {
            // if validated, throw an error
            if ( $existing_user->validated ) {
                throw new ValidationHttpException([
                    'email'=>'The email "'. $user->email. '" is already validated and in use. If needed, please visit the password reset page to reset your password.'
                ]);
            }
            // get existing verification token
            $token = $existing_user->getVerificationRequest();
            $user = $existing_user;

        } else {
            // new user
            $user->is_crypt_password = 1; // migration purpose
            $user->name = '';
            $user->roles = '[]';

            if ( !$user->fill(['password' => Hash::make($request->password)])->save() ) {
                throw new HttpException(500);
            }

            $token = $user->getVerificationRequest();
        }

        // send notification
        $user->notify(new VerifyEmail($token));

        return response()->json([
            'message' => 'Account created.  Please check your email to activate it.',
            'status_code' => 201
        ], 201);
    }

    /**
     * Verify user token
     *  
     * If successful user is set as validated.
     *
     * @authenticated
     * @queryParam token required string
     */
    public function verify($token) {

        try {
            // look for token request
            $request = UserRequest::where(DB::raw('MD5(requestId)'), '=', $token)->firstOrFail();

            // look for user, if not found delete the request,
            try {
                $user = User::findOrFail($request->userId);
            } catch (ModelNotFoundException $e) {
                $request->delete();
                throw new ConflictHttpException('No user is associated with this token');
            }

            // delete the user request
            $request->delete();
            // set user as validated
            $user->is_crypt_password = 1;
            $user->validated = 1;
            $user->save();

            event(new SignUp($user));

            return response()->json([
                'message' => 'Verified',
                'status_code' => 201
            ], 201);

        } catch (ModelNotFoundException $e) {
            throw new ConflictHttpException('Invalid request token');
        }
    }
}
