<?php
namespace App\Foundations\Fractal;

use League\Fractal\Serializer\ArraySerializer;

class NoDataArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        if ($resourceKey) {
            return $resourceKey == 'include' ? $data : [$resourceKey => $data];
        }
        return ['data' => $data];
    }

    public function item($resourceKey, array $data)
    {
        if ($resourceKey) {
            return $resourceKey == 'include' ? $data : [$resourceKey => $data];
        }
        return ['data' => $data];
    }
}