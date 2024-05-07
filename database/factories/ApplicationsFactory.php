<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applications>
 */
class ApplicationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory()->create()->id,
            'job_id' => \App\Models\JobPost::factory()->create()->id,
            'resume_path' => $this->faker->word . '.pdf', 
            'status' => $this->faker->randomElement(['applied', 'rejected', 'accepted']),
        ];
    }
}
