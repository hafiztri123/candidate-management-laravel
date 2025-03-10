<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CandidateApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_can_get_all_candidates()
    {
        Candidate::factory(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/candidates');

        $response->assertStatus(200)->assertJsonCount(5, 'data');
    }

    public function test_can_create_candidate()
    {
        $candidateData = Candidate::factory()->make()->toArray();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/candidates', $candidateData);

        // dump($response->json());

        $response->assertStatus(201)->assertJsonPath('data.name', $candidateData['name']);
    }

    public function test_can_search_candidates()
    {
        Candidate::factory()->create(['name' => 'John Smith', 'skills' => 'PHP, Laravel']);
        Candidate::factory()->create(['name' => 'Jane Doe', 'skills' => 'JavaScript, React']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/search/candidates?search=Laravel');

        $response->assertStatus(200)->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'John Smith');
    }

    public function test_candidate_is_soft_deleted()
    {
        $candidate = Candidate::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/candidates/{$candidate->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('candidates', ['id' => $candidate->id]);
    }

    public function test_can_get_trashed_candidates()
    {
        $candidate = Candidate::factory()->create();
        $candidate->delete();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/candidates/trashed");

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', $candidate->name)
            ->assertJsonCount(1, 'data');
    }


    public function test_can_restore_thrashed_candidate()
    {
        $candidate = Candidate::factory()->create();
        $candidate->delete();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->patch("/api/v1/candidates/{$candidate->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('candidates', [
            'id' => $candidate->id,
            'deleted_at' => null
        ]);
    }

    public function test_can_force_delete()
    {
        $candidate = Candidate::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/v1/candidates/{$candidate->id}/force");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('candidates', [
            'id' => $candidate->id
        ]);
    }




}
