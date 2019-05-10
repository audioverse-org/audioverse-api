<?php
namespace App\Transformers\Admin;

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
            'location' => $sponsor->location,
            'website' => $sponsor->website,
            'publicAddress' => $sponsor->publicAddress,
            'publicPhone' => $sponsor->publicPhone,
            'publicEmail' => $sponsor->publicEmail,
            'contactName' => $sponsor->contactName,
            'contactAddress' => $sponsor->contactAddress,
            'contactPhone' => $sponsor->contactPhone,
            'created' => $sponsor->created,
            'modified' => $sponsor->modified,
            'lang' => $sponsor->lang,
            'hiddenBySelf' => $sponsor->hiddenBySelf,
            'hidden' => $sponsor->hidden,
            'notes' => $sponsor->notes
        ];
    }
}