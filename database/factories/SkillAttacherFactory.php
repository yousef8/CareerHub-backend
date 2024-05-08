// database/factories/SkillAttacherFactory.php

namespace Database\Factories;

use App\Models\JobPost;

class SkillAttacherFactory
{
    /**
     * Attach skills to a job post.
     *
     * @param \App\Models\JobPost $jobPost
     * @param \Illuminate\Support\Collection $skills
     * @return void
     */
    public static function attachSkillsToJobPost(JobPost $jobPost, $skills): void
    {
        foreach ($skills as $skill) {
            $jobPost->skills()->attach($skill);
        }
    }
}
