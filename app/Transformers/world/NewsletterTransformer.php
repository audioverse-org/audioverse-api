<?php
namespace App\Transformers\World;

use App\Newsletter;
use League\Fractal\TransformerAbstract;

class NewsletterTransformer extends TransformerAbstract
{
    public function transform(Newsletter $newsletter) {

        return [
            'email' => $newsletter->email,
            'subscribed' => $newsletter->subscribed,
            'name' => $newsletter->name,
            'created' => $newsletter->subscribed,
            'modified' => $newsletter->modified,
        ];
    }
}