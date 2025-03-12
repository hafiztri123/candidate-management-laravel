<?php

namespace App\Notifications;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCandidateCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $candidate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Candidate Added')
                    ->line('A new candidate has been added to the system')
                    ->line('Name: ' . $this->candidate->name)
                    ->line('Email: ' . $this->candidate->email)
                    ->line('Current Position: ' . $this->candidate->current_position)
                    ->action('View Candidate', url('/candidates/' . $this->candidate->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'candidate_id' => $this->candidate->id,
            'candidate_name' => $this->candidate->name,
            'message' => 'New candidate ' . $this->candidate->name . ' has been added',
            'type' => 'candidate_created',
        ];
    }
}
