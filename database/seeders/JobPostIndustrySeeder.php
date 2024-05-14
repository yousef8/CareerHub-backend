<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use App\Models\JobPost;

class JobPostIndustrySeeder extends Seeder
{
    public function run()
    {
        $industries = Industry::all();

        foreach ($industries as $industry) {
            $jobPostCount = rand(1, 5);
            $existingJobPosts = JobPost::all()->random($jobPostCount);

            $industry->jobs()->attach($existingJobPosts->pluck('id'));
        }
    }
}
