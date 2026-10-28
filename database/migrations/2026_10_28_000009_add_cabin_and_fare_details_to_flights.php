<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->string('cabin_class', 24)->default('economy')->index();
            $table->unsignedTinyInteger('carry_on_kg')->default(7);
            $table->unsignedTinyInteger('checked_baggage_kg')->default(20);
            $table->string('fare_rules', 500)->nullable();
            $table->string('aircraft_type', 80)->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('flights', fn (Blueprint $table) => $table->dropColumn([
            'cabin_class', 'carry_on_kg', 'checked_baggage_kg', 'fare_rules', 'aircraft_type',
        ]));
    }
};
