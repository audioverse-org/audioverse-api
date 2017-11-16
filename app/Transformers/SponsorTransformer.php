<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Sponsor;

class SponsorTransformer extends TransformerAbstract {

    public function transform(Sponsor $sponsor) {

        return [
            'id' => $sponsor->sponsorId,
            'title' => $sponsor->title,
            'summary' => $sponsor->summary,
            'description' => $sponsor->description,
            'location' => $sponsor->location,
            'website' => $sponsor->website,
            'logo' => [
                'small' => $sponsor->logoSmall,
                'medium' => $sponsor->logoMedium,
                'large' => $sponsor->logoLarge,
            ],
        ];
    }
}