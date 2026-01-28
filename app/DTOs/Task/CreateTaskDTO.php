<?php

declare(strict_types=1);

namespace App\DTOs\Task;

use App\Enums\Task\TaskStatus;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as EnumRule;
use WendellAdriel\ValidatedDTO\Casting\CarbonImmutableCast;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class CreateTaskDTO extends ValidatedDTO
{
    public string $title;

    public string $user_id;

    public ?string $description;

    public ?TaskStatus $status;

    public ?CarbonImmutable $due_date;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable',  new EnumRule(TaskStatus::class)],
            'user_id' => ['required', 'uuid', Rule::exists('users', 'id')],
            'due_date' => ['nullable', 'date'],
        ];
    }

    protected function defaults(): array
    {
        return [
            'status' => TaskStatus::PENDING->value,
        ];
    }

    protected function casts(): array
    {
        return [
            'title' => new StringCast(),
            'description' => new StringCast(),
            'status' => new EnumCast(TaskStatus::class),
            'user_id' => new StringCast(),
            'due_date' => new CarbonImmutableCast(),
        ];
    }
}
