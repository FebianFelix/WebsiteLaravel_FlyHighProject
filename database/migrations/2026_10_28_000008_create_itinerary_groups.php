<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('reference', 24)->unique();
            $table->uuid('request_key');
            $table->unique(['user_id','request_key']);
            $table->string('status', 20)->default('pending');
            $table->unsignedBigInteger('total_price')->default(0);
            $table->timestamps();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('itinerary_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('leg_number')->nullable();
            $table->index(['itinerary_id','leg_number']);
        });
    }
    public function down(): void
    {
        Schema::table('bookings', fn (Blueprint $table) => $table->dropConstrainedForeignId('itinerary_id'));
        Schema::table('bookings', fn (Blueprint $table) => $table->dropColumn('leg_number'));
        Schema::dropIfExists('itineraries');
    }
};
