<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

readonly class NoContentResponse implements Responsable
{
    public function __construct(
        private int $code = Response::HTTP_NO_CONTENT,
        private array $headers = []
    ) {}

    public function toResponse($request): Response
    {
        return response()->noContent($this->code, $this->headers);
    }
}
