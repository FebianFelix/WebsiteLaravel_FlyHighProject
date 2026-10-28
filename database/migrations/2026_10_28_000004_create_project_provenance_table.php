<?php

// Project attribution: Copyright (c) 2026 FebianFelix.
// https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_provenance', function (Blueprint $table) {
            $table->id();
            $table->string('project', 100);
            $table->string('original_author', 100);
            $table->string('canonical_repository', 255);
            $table->timestamp('created_at')->useCurrent();
        });
        DB::table('project_provenance')->insert([
            'project' => 'FlyHigh', 'original_author' => 'FebianFelix',
            'canonical_repository' => 'https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject',
            'created_at' => now(),
        ]);
    }
    public function down(): void { Schema::dropIfExists('project_provenance'); }
};
