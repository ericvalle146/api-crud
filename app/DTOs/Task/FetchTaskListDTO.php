<?php

declare(strict_types=1);

namespace App\DTOs\Task;

use App\DTOs\Common\PaginationDTO;
use App\Enums\Task\TaskStatus;
use Illuminate\Validation\Rules\Enum as EnumRule;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;

class FetchTaskListDTO extends PaginationDTO
{
    public ?TaskStatus $status;

    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => ['string', 'nullable', 'sometimes', new EnumRule(TaskStatus::class)],
        ]);
    }

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'status' => new EnumCast(TaskStatus::class),
        ]);
    }
}
