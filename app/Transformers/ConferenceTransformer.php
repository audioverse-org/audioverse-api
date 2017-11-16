<?php


namespace App\Transformers;


use League\Fractal\TransformerAbstract;
use App\Conference;

class ConferenceTransformer extends TransformerAbstract
{
    public function transform(Conference $conference) {

        return [
            'id' => $conference->conferenceId,
            'title' => $conference->title,
            'summary' => $conference->summary,
            'description' => $conference->description,
            'logo' => [
                'small' => $conference->logoSmall,
                'medium' => $conference->logoMedium,
                'large' => $conference->logoLarge,
            ],
        ];
    }
}