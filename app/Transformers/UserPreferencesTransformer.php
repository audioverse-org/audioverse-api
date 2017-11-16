<?php
namespace App\Transformers;

use App\UserPreferences;
use League\Fractal\TransformerAbstract;


class UserPreferencesTransformer extends TransformerAbstract
{
    public function transform(UserPreferences $preferences) {

        return [
            'site' => $preferences->site,
            'region' => $preferences->region,
            'timezone' => $preferences->timezone,
        ];
    }
}