namespace Database\Seeders;

use App\Models\JobPost;
use Illuminate\Database\Seeder;
use Database\Factories\SkillSelectorFactory;
use Database\Factories\SkillAttacherFactory;

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
            $skills = SkillSelectorFactory::selectRandomSkills();

            // Attach each selected skill to the job post
            SkillAttacherFactory::attachSkillsToJobPost($jobPost, $skills);
        }
    }
}
