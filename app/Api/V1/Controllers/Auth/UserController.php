<?php

/**
 * User resource representation.
 *
 * @Resource("User", uri="/auth/user")
 */

namespace App\Api\v1\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use Helpers;

    /**
     * Returns user
     *
     * Returns authenticated user data
     *
     * @Post("/")
     * @Versions({"v1"})
     */
    public function user() {

        $user = app('Dingo\Api\Auth\Auth')->user();
        return $this->response->item($user, new UserTransformer);
    }

    public function update(Request $request) {

        $user = app('Dingo\Api\Auth\Auth')->user();

        if ( $user->email == 'audioverseministry@gmail.com' ) {
            // validate password

            try {

                $user = User::findOrFail($request->get('userId'));

                if ( $request->exists('new_password') ) {
                    $this->validate($request, [
                        'new_password' => 'required|min:6'
                    ]);
                    $user->password = Hash::make($request->new_password);
                }

                $user->firstName = $request->get('firstName');
                $user->lastName = $request->get('lastName');
                $user->addressLine1 = $request->get('addressLine1');
                $user->addressLine2 = $request->get('addressLine2');
                $user->municipality = $request->get('municipality');
                $user->province = $request->get('province');
                $user->postalCode = $request->get('postalCode');
                $user->country = $request->get('country');

                $user->save();

                if ( $request->get('subscribed') == 1 ) {
                    $user->subscribeNewsletter();
                } else {
                    $user->unsubscribeNewsletter();
                }

                return response()->json([
                    'message' => 'Account Updated',
                    'status_code' => 200
                ], 200);

            } catch( ModelNotFoundException $e) {
                return $this->response->errorNotFound("User not found");
            }

        } else {

            return $this->response->errorNotFound("Not Authorized");
        }

    }
    /**
     * Temporary Administrative update password function
     * @param Request $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function update_password(Request $request) {

        $user = app('Dingo\Api\Auth\Auth')->user();

        if ( $user->email == 'audioverseministry@gmail.com' ) {
            // validate password
            $this->validate($request, [
                'new_password' => 'required|min:6'
            ]);

            $user = User::where('email', '=', $request->get('email'))->first();

            if (!$user) {
                return $this->response->errorNotFound("User not found");
            }

            $user->is_crypt_password = 1;
            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();
            return $this->response->created();

        } else {

            return $this->response->errorNotFound("Not Authorized");
        }
    }
}