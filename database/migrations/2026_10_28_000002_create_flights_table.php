<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number', 20)->unique();
            $table->string('airline', 100);
            $table->string('origin', 100);
            $table->string('destination', 100);
            $table->dateTime('departure_at')->index();
            $table->dateTime('arrival_at');
            $table->unsignedBigInteger('price');
            $table->unsignedSmallInteger('seats_total');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
