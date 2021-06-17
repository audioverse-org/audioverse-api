<?php
namespace App\Transformers\Joins;

use League\Fractal\TransformerAbstract;
use App\Sponsor;

class SponsorIncludeTransformer extends TransformerAbstract {

    public function transform(Sponsor $sponsor) {

        return [
            'title' => $sponsor->title,
            'logo' => [
                'small' => $sponsor->logoSmall,
                'medium' => $sponsor->logoMedium,
                'large' => $sponsor->logoLarge,
            ],
        ];
    }
}