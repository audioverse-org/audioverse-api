<?php
namespace App\Transformers\World;

use League\Fractal\TransformerAbstract;
use App\Presenter;

class PresenterTransformer extends TransformerAbstract {

    public function transform(Presenter $presenter) {

        return [
            'id' => $presenter->personId,
            'name' => $presenter->nameGnfCasual,
            'summary' => $presenter->summary,
            'description' => $presenter->description,
            'website' => $presenter->website,
            'logo' => [
                'small' => $presenter->logoSmall,
                'medium' => $presenter->logoMedium,
                'large' => $presenter->logoLarge,
            ],
        ];
    }
}