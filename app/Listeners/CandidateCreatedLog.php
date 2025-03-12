<?php

namespace App\Listeners;

use App\Events\CandidateCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CandidateCreatedLog
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
    public function handle(CandidateCreated $event): void
    {
        Log::info("Candidate created", [
            'candidate_id' => $event->candidate->id,
            'user_id' => $event->user->id ?? 'Guest'
        ]);
    }
}
