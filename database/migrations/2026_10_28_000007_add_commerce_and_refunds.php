<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->enum('type', ['fixed', 'percentage']);
            $table->unsignedInteger('value');
            $table->unsignedInteger('max_uses')->nullable();
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('promo_code', 40)->nullable()->index();
            $table->unsignedBigInteger('base_fare')->default(0);
            $table->unsignedBigInteger('tax_amount')->default(0);
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->string('stripe_session_id', 120)->nullable()->unique();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->string('external_reference', 120)->nullable()->index();
        });
        Schema::create('payment_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 40);
            $table->string('event_id', 140);
            $table->string('status', 30);
            $table->string('description', 240)->nullable();
            $table->timestamps();
            $table->unique(['provider', 'event_id']);
        });
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason');
            $table->string('status', 30)->default('requested');
            $table->unsignedBigInteger('amount');
            $table->string('provider_reference', 120)->nullable();
            $table->timestamps();
            $table->index(['status','created_at']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
        Schema::dropIfExists('payment_events');
        Schema::table('payments', fn (Blueprint $table) => $table->dropColumn('external_reference'));
        Schema::table('bookings', fn (Blueprint $table) => $table->dropColumn(['promo_code', 'base_fare', 'tax_amount', 'discount_amount', 'stripe_session_id']));
        Schema::dropIfExists('promo_codes');
    }
};
