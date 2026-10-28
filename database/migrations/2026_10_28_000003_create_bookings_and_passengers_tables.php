<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('flight_id')->constrained()->restrictOnDelete();
            $table->string('reference', 20)->unique();
            $table->uuid('request_key');
            $table->unique(['user_id', 'request_key']);
            $table->string('status', 20)->default('pending')->index();
            $table->unsignedTinyInteger('seats');
            $table->unsignedBigInteger('total_price');
            $table->dateTime('expires_at')->nullable()->index();
            $table->dateTime('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('full_name', 120);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->restrictOnDelete();
            $table->string('provider', 40);
            $table->string('provider_reference', 80)->unique();
            $table->string('status', 32);
            $table->unsignedBigInteger('amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('passengers');
        Schema::dropIfExists('bookings');
    }
};
