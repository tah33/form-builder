<?php

namespace App\Traits;


trait ResponseFormatTrait
{
    public function error($code, $message, $validation_errors = []): array
    {
        return [
            'status'    => $code,
            'message'   => $message,
        ];
    }
}
