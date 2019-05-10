<?php
namespace App\Transformers\World;

use App\UserSocialAuth;
use League\Fractal\TransformerAbstract;

class UserSocialAuthTransformer extends TransformerAbstract
{
    public function transform(UserSocialAuth $socialAuth) {

        return [
            'socialId' => $socialAuth->socialId,
            'socialServiceName' => $socialAuth->socialServiceName,
            'sessionToken' => $socialAuth->sessionToken,
        ];
    }
}