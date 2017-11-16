<?php

namespace App\Api\V1\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Api\V1\Requests\ForgotPasswordRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ForgotPasswordController extends Controller
{
    public function sendResetEmail(ForgotPasswordRequest $request)
    {
        $user = User::where('email', '=', $request->get('email'))->first();

        if(!$user) {
            return response()->json([
                'message' => 'No account found.',
                'status_code' => 404
            ], 404);
        }

        $broker = $this->getPasswordBroker();
        $sendingResponse = $broker->sendResetLink($request->only('email'));

        if($sendingResponse !== Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password-reset request has expired or is not valid.  Please submit a new request.',
                'status_code' => 500
            ], 500);
        }

        return response()->json([
            'message' => 'Request initiated.  Please check your email for further instructions.',
            'status_code' => 200
        ], 200);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    private function getPasswordBroker()
    {
        return Password::broker();
    }
}
