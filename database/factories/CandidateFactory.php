<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    protected $model = Candidate::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'skills' => $this->faker->words(3, true),
            'experience' => $this->faker->sentence,
            'current_position' => $this->faker->jobTitle,
            'education' => $this->faker->word,
            'status' => $this->faker->randomElement(['new', 'contacted', 'interviewing', 'offered', 'hired', 'rejected']),

        ];
    }
}
