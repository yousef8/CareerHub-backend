<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\JobPost;
use App\Models\Industry;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_industry', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JobPost::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Industry::class)->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['job_post_id', 'industry_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_industry');
    }
};
