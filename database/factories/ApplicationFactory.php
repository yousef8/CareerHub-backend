<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\JobPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applications>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::pluck('id')->random(),
            'job_id' => JobPost::pluck('id')->random(),
            'resume_path' => 'storage/candidate-resumes/' . $this->faker->word . '.pdf',
            'status' => $this->faker->randomElement(['pending', 'rejected', 'accepted']),
        ];
    }
}
