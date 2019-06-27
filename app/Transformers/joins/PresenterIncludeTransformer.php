<?php
namespace App\Transformers\Joins;

use League\Fractal\TransformerAbstract;
use App\Presenter;

class PresenterIncludeTransformer extends TransformerAbstract {

    public function transform(Presenter $presenter) {

        return [
            'firstName' => $presenter->givenName,
            'lastName' => $presenter->surname,
            'hiragana' => $presenter->hiragana,
        ];
    }
}