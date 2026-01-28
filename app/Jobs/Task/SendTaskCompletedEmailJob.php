<?php

declare(strict_types=1);

namespace App\Jobs\Task;

use App\Actions\User\FetchUser;
use App\Mail\Task\TaskCompletedMail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendTaskCompletedEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Task $task,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(FetchUser $fetch_user): void
    {
        $user = $fetch_user->handle($this->task->user_id);

        Mail::to($user->email)
            ->send(new TaskCompletedMail($user, $this->task));
    }
}
