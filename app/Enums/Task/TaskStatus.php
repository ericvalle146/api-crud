<?php

declare(strict_types=1);

namespace App\Enums\Task;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::COMPLETED,
        ];

    }

    public static function toArray(): array
    {
        return array_column(TaskStatus::cases(), 'value');
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::IN_PROGRESS => 'Em Progresso',
            self::COMPLETED => 'Concluída',
        };
    }
}
