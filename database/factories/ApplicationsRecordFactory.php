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
            'user_id' => $this->faker->randomNumber(),
            'job_id' => $this->faker->randomNumber(),
            'resume_path' => $this->faker->word . '.pdf', 
            'status' => $this->faker->randomElement(['applied', 'rejected', 'accepted']),
        ];
    }
}
