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
        ])->getJson('/api/candidates');

        $response->assertStatus(200)->assertJsonCount(5, 'data');
    }

    public function test_can_create_candidate()
    {
        $candidateData = Candidate::factory()->make()->toArray();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/candidates', $candidateData);

        // dump($response->json());

        $response->assertStatus(201)->assertJsonPath('data.name', $candidateData['name']);
    }

    public function test_can_search_candidates()
    {
        Candidate::factory()->create(['name' => 'John Smith', 'skills' => 'PHP, Laravel']);
        Candidate::factory()->create(['name' => 'Jane Doe', 'skills' => 'JavaScript, React']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/candidates?search=Laravel');

        $response->assertStatus(200)->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'John Smith');
    }

}
