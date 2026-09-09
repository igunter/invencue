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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // No numeric FK: the games table has no id/primary key of its own
            // (everything references it by slug — see games.slug + category_slug/
            // age_range_slug elsewhere), so this follows the same convention as
            // game_results.game_slug rather than a foreign key constraint.
            $table->string('game_slug');
            $table->string('type');
            $table->text('question');
            $table->string('answer');
            $table->json('choices')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['game_slug', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
