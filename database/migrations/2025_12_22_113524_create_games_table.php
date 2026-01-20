<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->enum('platform', ['PC', 'Mobile', 'Console'])->nullable();
            $table->boolean('status')->default(1)->comment('0 = Inactive, 1 = Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};