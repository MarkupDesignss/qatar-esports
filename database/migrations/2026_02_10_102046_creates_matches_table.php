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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tournament_id')
                ->constrained()
                ->cascadeOnDelete();

            // Teams (from tournament_registrations)
            $table->foreignId('team1_id')
                ->nullable()
                ->constrained('tournament_registrations')
                ->nullOnDelete();

            $table->foreignId('team2_id')
                ->nullable()
                ->constrained('tournament_registrations')
                ->nullOnDelete();

            // Round info
            $table->string('round');
            $table->video('match_video');
            $table->date('match_date');
            $table->time('match_time');
            // Example: Round of 1, Quarterfinal, Semifinal, Final

            $table->integer('match_order')->default(1);
            // bracket order (1,2,3...)

            // Result
            $table->foreignId('winner_id')
                ->nullable()
                ->constrained('tournament_registrations')
                ->nullOnDelete();

            $table->enum('status', ['pending', 'live', 'completed'])
                ->default('pending');

            $table->timestamp('played_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};