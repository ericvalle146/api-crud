<?php

declare(strict_types=1);

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'data' => new UserResource($this['data']),
            'token' => $this['token'],
        ];
    }
}
