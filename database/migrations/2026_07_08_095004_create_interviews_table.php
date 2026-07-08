<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_posting_id')->nullable()->constrained()->nullOnDelete();
            $table->string('interview_type');
            $table->string('title');
            $table->dateTime('scheduled_at');
            $table->unsignedInteger('duration_minutes')->default(60);
            $table->string('interviewer_name')->nullable();
            $table->string('interviewer_email')->nullable();
            $table->string('location_or_link')->nullable();
            $table->string('meeting_platform')->nullable();
            $table->string('meeting_link')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled');
            $table->text('feedback')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
