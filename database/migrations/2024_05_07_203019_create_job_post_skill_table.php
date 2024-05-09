use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_post_skill', function (Blueprint $table) {
            $table->foreignIdFor('job_posts', 'job_post_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor('skills', 'skill_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['job_post_id', 'skill_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_post_skill');
    }
};
