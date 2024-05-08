<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class JobPostSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This method is responsible for attaching random skills to each job post.
     */
    public function run(): void
    {
        // Loop through each job post in the database
        foreach (JobPost::all() as $jobPost) {
            // Select a random set of skills for the current job post
            // The number of skills to select is randomly chosen between 5 and 12
            $skills = Skill::inRandomOrder()->take(rand(5, 12))->pluck('id');

            // Attach each selected skill to the job post
            // This is done by creating records in the pivot table that associates the job post with each skill
            foreach ($skills as $skill) {
                $jobPost->skills()->attach($skill);
            }
        }
    }

}
