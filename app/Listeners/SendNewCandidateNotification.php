<?php

namespace App\Listeners;

use App\Events\CandidateCreated;
use App\Models\User;
use App\Notifications\NewCandidateCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewCandidateNotification implements ShouldQueue
{
    use InteractsWithQueue;
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
    public function handle(CandidateCreated $event): void
    {
        $hrAdmins = User::whereHas('roles', function ($query) {
            $query->where('slug', 'admin');
        })->get();

        Notification::send($hrAdmins, new NewCandidateCreated($event->candidate));
    }
}
