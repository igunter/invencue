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
        Schema::create('games', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->string('age_range_slug');
            $table->string('category_slug');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('blurb');
            $table->timestamps();

            $table->foreign('age_range_slug')->references('slug')->on('age_ranges');
            $table->foreign('category_slug')->references('slug')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
