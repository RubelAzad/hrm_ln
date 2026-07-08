<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_exits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('exit_type');
            $table->date('notice_date');
            $table->date('exit_date');
            $table->text('reason')->nullable();
            $table->boolean('is_voluntary')->default(true);
            $table->date('exit_interview_date')->nullable();
            $table->text('exit_interview_notes')->nullable();
            $table->decimal('settlement_amount', 12, 2)->nullable();
            $table->string('clearance_status')->default('pending');
            $table->text('clearance_notes')->nullable();
            $table->boolean('rehire_eligible')->default(true);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_exits');
    }
};
