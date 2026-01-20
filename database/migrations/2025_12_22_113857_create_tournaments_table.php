<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('game_id')
            ->nullable()
            ->constrained('games')
            ->nullOnDelete();

        $table->string('title')->nullable();
        $table->string('slug')->nullable()->unique();

        $table->string('logo')->nullable();
        $table->string('banner')->nullable();

        $table->string('location')->nullable();

        $table->enum('format', ['solo', 'team'])->nullable();
        $table->integer('team_size')->nullable();

        $table->enum('status', ['upcoming', 'live', 'completed'])->default('upcoming');
        $table->enum('visibility', ['draft', 'published', 'archived'])->default('draft');

        $table->boolean('is_featured')->default(false);
        $table->boolean('is_registration_open')->default(true);

        $table->dateTime('registration_start')->nullable();
        $table->dateTime('registration_end')->nullable();

        $table->dateTime('start_date')->nullable();
        $table->dateTime('end_date')->nullable();
        $table->time('start_time')->nullable();

        $table->string('timezone')->nullable();

        $table->decimal('entry_fee', 10, 2)->nullable();
        $table->decimal('prize_pool', 12, 2)->nullable();

        $table->integer('max_participants')->nullable();
        $table->integer('registered_participants')->default(0);

        $table->longText('description')->nullable();
        $table->longText('rules')->nullable();

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('admins')
            ->nullOnDelete();

        $table->timestamps();
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};