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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained();
            /*
            // extended form for film_id, user_id
            $table->unsignedBigInteger('film_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('film_id')->references('id')->on('films')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
            */
            $table->string('text', 400);
            $table->unsignedTinyInteger('rating')->nullable();
            $table->foreignId('comment_id')->nullable()->constrained()->cascadeOnDelete();
            /*
            // extended form for comment_id
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->foreign('comment_id')->references('id')->on('comments')->cascadeOnDelete();
            */
            $table->timestamps();

            // constrained rating range(1, 10) in Request?
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
