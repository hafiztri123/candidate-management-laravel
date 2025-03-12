<?php

namespace App\Listeners;

use App\Events\CandidateCreated;
use App\Events\CandidateDeleted;
use App\Events\CandidateForceDeleted;
use App\Events\CandidateRestored;
use App\Events\CandidateStatusChanged;
use App\Events\CandidateUpdated;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogCandidateActivity implements ShouldQueue
{
    /**
     * Create the event listener.
     */

    use InteractsWithQueue;
    
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(CandidateCreated|CandidateDeleted|CandidateForceDeleted|CandidateRestored|CandidateStatusChanged|CandidateUpdated $event): void
    {

        $activityData = [
            'user_id' => $event->user ? $event->user->id : null,
            'user_name' => $event->user ? $event->user->name : 'System',
            'subject_type' => 'candidate',
        ];

        if (isset($event->candidate) && $event->candidate->id) {
            $activityData['subject_id'] = $event->candidate->id;
        }

        switch (get_class($event)) {
            case CandidateCreated::class:
                $activityData['action'] = 'created';
                $activityData['details'] = json_encode([
                    'candidate_name' => $event->candidate->name,
                    'candidate_email' => $event->candidate->email
                ]);
                break;

            case CandidateUpdated::class:
                $activityData['action'] = 'updated';
                $activityData['details'] = json_encode([
                    'candidate_name' => $event->candidate->name,
                    'old_data' => $event->oldData,
                    'new_data' => $event->newData
                ]);
                break;

            case CandidateDeleted::class:
                $activityData['action'] = 'deleted';
                $activityData['details'] = json_encode([
                    'candidate_name' => $event->candidate->name,
                    'candidate_email' => $event->candidate->email
                ]);
                break;

            case CandidateForceDeleted::class:
                $activityData['action'] = 'force_deleted';
                $activityData['details'] = json_encode([
                        'candidate_name' => $event->candidate->name,
                        'candidate_email' => $event->candidate->email
                    ]);
                break;

            case CandidateRestored::class:
                $activityData['action'] = 'restored';
                $activityData['details'] = json_encode([
                        'candidate_name' => $event->candidate->name,
                        'candidate_email' => $event->candidate->email
                ]);
                break;

            case CandidateStatusChanged::class:
                $activityData['action'] = 'status_changed';
                $activityData['details'] = json_encode([
                        'candidate_name' => $event->candidate->name,
                        'oldStatus' => $event->oldStatus,
                        'newStatus' => $event->newStatus
                ]);
                break;
        }

        ActivityLog::create($activityData);





    }
}
