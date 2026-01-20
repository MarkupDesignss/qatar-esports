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
        Schema::create('users', function (Blueprint $table) {
             $table->id();

            // Signup fields
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('mobile', 15)->unique();

            // Authentication
            $table->string('password');

            // Email OTP (Forgot / Verify / Reset)
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();

            // Account status
            $table->boolean('status')->default(1)->comment('0 = Inactive, 1 = Active');

            // Token based auth (for Get User API)
            $table->string('api_token', 80)->nullable()->unique();

            // Laravel defaults
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index(['email', 'otp']);
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};