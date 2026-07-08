<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_position')->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('education_level')->nullable();
            $table->json('skills')->nullable();
            $table->string('resume_path')->nullable();
            $table->json('resume_parsed_data')->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('talent_pool_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
