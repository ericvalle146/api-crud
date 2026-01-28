<?php

declare(strict_types=1);

namespace App\Listeners\Task;

use App\Events\Task\TaskCompleted;
use App\Jobs\Task\SendTaskCompletedEmailJob;

class SendTaskCompletedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCompleted $event): void
    {
        SendTaskCompletedEmailJob::dispatch($event->task)
            ->delay(now()->addSeconds(5));
    }
}
