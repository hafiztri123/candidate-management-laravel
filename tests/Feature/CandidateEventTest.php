<?php

namespace Tests\Feature;

use App\Events\CandidateCreated;
use App\Listeners\CandidateCreatedLog;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CandidateEventTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     use RefreshDatabase;

     protected $user;
     protected $userToken;

     protected function setUp(): void
     {
        parent::setUp();

        Event::fake();

        Log::spy();

        $this->user = User::factory()->create();
        $this->userToken = $this->user->createToken('event-token')->plainTextToken;
     }

     public function test_candidate_created_event()
     {
        Event::fakeFor(function () {
            $candidate = Candidate::factory()->make()->toArray();

            $this->withHeaders([
                'Authorization' => 'Bearer' . $this->userToken
            ])->post('/api/v1/candidates', $candidate);

            Event::assertDispatched(CandidateCreated::class, function ($event) use ($candidate) {
                return $event->candidate->name === $candidate['name'];
            });
        });
    }

     public function test_candidate_created_listener_is_called()
     {
        $candidate = Candidate::factory()->create()->toArray();

        $this->withHeaders([
            'Authorization' => 'Bearer' . $this->userToken
        ])->post('/api/v1/candidates', $candidate);

        Event::assertListening(
            CandidateCreated::class,
            CandidateCreatedLog::class
        );

        Log::shouldReceive('info')->with(
            'Candidate created',[
                'candidate_id' => $candidate['id'],
                'user_id' => $candidate->user_id ?? 'Guest',
            ]
        );
     }
}
