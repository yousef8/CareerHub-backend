<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applications>
 */
class ApplicationsRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomNumber(),
            'job_id' => fake()->randomNumber(),
            'resume_path' =>fake()->word . '.pdf', 
            'status' => fake()->randomElement(['applied', 'rejected', 'accepted']),
        ];
    }
}
