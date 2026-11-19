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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('imdb_id')->unique();
            $table->string('poster_image')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('background_image')->nullable();
            $table->string('background_color', 9)->nullable()->default('#ffffff');
            $table->string('video_link')->nullable();
            $table->string('preview_video_link')->nullable();
            $table->string('description', 1000)->nullable(); // or text?
            $table->string('director')->nullable();
            $table->json('starring')->nullable();
            $table->integer('run_time')->nullable();
            $table->integer('released')->nullable();
            $table->enum('status', ['pending', 'moderate', 'ready'])->default('pending');
            $table->boolean('is_promo')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
