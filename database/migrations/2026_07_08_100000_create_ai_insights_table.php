<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->morphs('insightable');
            $table->string('type');
            $table->json('data');
            $table->decimal('score', 5, 2)->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['insightable_type', 'insightable_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_insights');
    }
};
