<?php

declare(strict_types=1);

namespace App\DTOs\Task;

use App\Enums\Task\TaskStatus;
use Illuminate\Validation\Rules\Enum as EnumRule;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class FetchTaskListDTO extends ValidatedDTO
{
    public ?TaskStatus $status;

    public ?int $page;

    public ?int $per_page;

    protected function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer'],
            'per_page' => ['sometimes', 'integer'],
            'status' => ['string', 'nullable', 'sometimes', new EnumRule(TaskStatus::class)],
        ];
    }

    protected function defaults(): array
    {
        return [
            'page' => 1,
            'per_page' => 15,
            'status' => null,
        ];
    }

    protected function casts(): array
    {
        return [
            'page' => new IntegerCast(),
            'per_page' => new IntegerCast(),
            'status' => new EnumCast(TaskStatus::class),

        ];
    }
}
