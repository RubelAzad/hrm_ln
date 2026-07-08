<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('asset_type');
            $table->string('asset_name');
            $table->string('asset_serial')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->json('specification')->nullable();
            $table->date('assigned_date');
            $table->date('return_date')->nullable();
            $table->string('condition_at_assignment')->default('good');
            $table->string('condition_at_return')->nullable();
            $table->string('status')->default('assigned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_assets');
    }
};
