<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Interfaces\LMSInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncUserToLMS implements ShouldQueue
{
    public function __construct(public LMSInterface $lms)
    {
    }

    public function handle(UserUpdated|UserCreated $event) : void
    {
        if (!$event->user->lms_id) {
            // if the user doesn't exist on the LMS yet, create them
            $this->lms->createUser($event->user, $event->password ?? '');
        } else {
            // otherwise, perform an update.
            $this->lms->updateUser($event->user, $event->password ?? '');
        }
    }
}
