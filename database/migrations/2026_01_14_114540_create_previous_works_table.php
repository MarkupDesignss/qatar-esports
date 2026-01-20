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
        Schema::create('previous_works', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable(); // Valorant Seasons
            $table->string('title');
            $table->date('event_date')->nullable();
            $table->text('description')->nullable();
            $table->string('image'); // thumbnail / banner
            $table->string('video_url')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_works');
    }
};
