<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('grace_minutes')->default(0);
            $table->unsignedSmallInteger('late_threshold_minutes')->default(15);
            $table->unsignedSmallInteger('early_leave_threshold_minutes')->default(15);
            $table->unsignedSmallInteger('break_duration_minutes')->default(60);
            $table->decimal('work_hours', 4, 1)->default(8.0);
            $table->boolean('overtime_allowed')->default(true);
            $table->decimal('overtime_rate', 3, 2)->default(1.50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
