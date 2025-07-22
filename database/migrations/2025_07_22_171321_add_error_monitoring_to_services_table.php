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
        Schema::table('services', function (Blueprint $table) {
            $table->json('error_patterns')->nullable()->after('check_interval');
            $table->json('http_headers')->nullable()->after('error_patterns');
            $table->integer('timeout')->default(10)->after('http_headers');
            $table->boolean('follow_redirects')->default(true)->after('timeout');
            $table->string('expected_status_codes')->default('200-299')->after('follow_redirects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'error_patterns',
                'http_headers',
                'timeout',
                'follow_redirects',
                'expected_status_codes'
            ]);
        });
    }
};
