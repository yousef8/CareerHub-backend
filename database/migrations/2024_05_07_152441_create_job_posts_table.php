<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('requirements');
            $table->string('city');
            $table->string('country');
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
            $table->unsignedTinyInteger('min_exp_years')->nullable();
            $table->unsignedTinyInteger('max_exp_years')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->enum('type', ['full-time', 'part-time', 'contract']);
            $table->enum('remote_type', ['on-site', 'hybrid', 'remote']);
            $table->enum('experience_level', ['entry_level', 'associate', 'mid-senior', 'director', 'executive']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
