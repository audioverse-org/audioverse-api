<?php
namespace App\Transformers\Joins;

use League\Fractal\TransformerAbstract;
use App\Conference;

class ConferenceIncludeTransformer extends TransformerAbstract {

    public function transform(Conference $conference) {

        return [
            'title' => $conference->title,
            'logo' => [
                'small' => $conference->logoSmall,
                'medium' => $conference->logoMedium,
                'large' => $conference->logoLarge,
            ],
        ];
    }
}