<?php

declare(strict_types=1);

namespace App\Listeners\User;

use App\Events\User\UserCreated;
use App\Jobs\User\SendWelcomeEmailJob;

class SendUserWelcomeNotification
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
    public function handle(UserCreated $event): void
    {
        SendWelcomeEmailJob::dispatch($event->user)
            ->delay(now()->addSeconds(5));
    }
}
