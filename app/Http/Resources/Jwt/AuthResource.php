<?php

namespace App\Http\Resources\Jwt;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'status'   => $this->resource['status'] ?? $this->getStatus(),
            'message'  => $this->resource['message'] ?? $this->getMessage(),
        ];
    }

    private function getStatus(): bool
    {
        return false;
    }

    private function getMessage(): string
    {
        return 'token de autenticação esta incorreto!';
    }
}
