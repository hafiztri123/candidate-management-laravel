<?php

namespace App\Events;

use App\Models\Candidate;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CandidateStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $candidate;
    public $oldStatus;
    public $newStatus;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Candidate $candidate, string $oldStatus, string $newStatus, $user = null)
    {
        $this->candidate = $candidate;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->user = $user ?? Auth::user();
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
