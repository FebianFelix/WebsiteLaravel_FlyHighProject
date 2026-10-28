<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 5)->default('id');
            $table->string('currency', 3)->default('IDR');
            $table->string('timezone', 64)->default('Asia/Jakarta');
            $table->boolean('email_updates')->default(false);
            $table->string('seat_preference', 12)->default('any');
        });
        Schema::create('site_settings', function (Blueprint $table) {
            $table->string('key', 80)->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        Schema::create('content_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 90)->unique();
            $table->string('title', 160);
            $table->text('body');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        Schema::create('audit_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 90);
            $table->string('subject', 120)->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
            $table->index(['action', 'created_at']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('audit_events');
        Schema::dropIfExists('content_pages');
        Schema::dropIfExists('site_settings');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['locale', 'currency', 'timezone', 'email_updates', 'seat_preference']));
    }
};
