<?php

namespace App\Transformers;
use League\Fractal\TransformerAbstract;
use App\MediaFile;

class MediaFileTransformer extends TransformerAbstract {

    public function transform(MediaFile $mediaFiles) {

        return [
            'id' => $mediaFiles->fileId,
            'name' => $mediaFiles->filename,
            'size' => $mediaFiles->filesize,
            'bitrate' => $mediaFiles->bitrate,
            'container' => $mediaFiles->container,
            'duration' => $mediaFiles->duration,
            'download_url' => $mediaFiles->downloadUrl,
        ];
    }

}