<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnauthenticatedResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'error' => 'Unauthenticated'
        ];
    }
}
