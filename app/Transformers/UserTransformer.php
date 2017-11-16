<?php
namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'socialAuth',
        'newsletter',
    ];

    public function transform(User $user) {

        $data = [
            'userId' => $user->userId,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'username' => $user->username,
            'email' => $user->email,
            'addressLine1' => $user->addressLine1,
            'addressLine2' => $user->adddressLine2,
            'municipality' => $user->city,
            'province' => $user->province,
            'postalCode' => $user->zip,
            'country' => $user->country,
            'language' => $user->language,
            'validated' => $user->validated,
            'root' => $user->root,
            'groups' => $user->groups,
            'ownerOf' => $user->ownerOf,
            'created' => $user->created,
            'modified' => $user->modified,
            'newsletter' => $user->newsletter,
        ];

        if ( isset($user->socialAuth) ) {
            $data['sessionToken'] = $user->socialAuth->sessionToken;
            $data['socialId'] = $user->socialAuth->sessionToken;
            $data['socialServiceName'] = $user->socialAuth->sessionToken;
        }
        return $data;
    }

    public function includeSocialAuth(User $user)
    {
        $socialAuth = $user->socialAuth;
        if ( $socialAuth ) {
            return $this->item($socialAuth, new UserSocialAuthTransformer);
        }
    }

    public function includeNewsletter(User $user)
    {
        $newsletter = $user->newsletter;

        if ( $newsletter ) {
            return $this->item($newsletter, new NewsletterTransformer);
        }
    }
}