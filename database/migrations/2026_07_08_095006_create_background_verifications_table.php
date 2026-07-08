<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('background_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('vendor')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('initiated_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('report_path')->nullable();
            $table->text('report_summary')->nullable();
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('background_verifications');
    }
};
