// database/factories/SkillSelectorFactory.php

namespace Database\Factories;

use App\Models\Skill;

class SkillSelectorFactory
{
    /**
     * Select a random set of skills.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function selectRandomSkills()
    {
        return Skill::inRandomOrder()->take(rand(5, 12))->pluck('id');
    }
}
