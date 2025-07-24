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
            // Custom scheduling options
            $table->string('schedule_type')->default('interval')->after('check_interval'); // 'interval', 'cron', 'event'
            $table->string('cron_expression')->nullable()->after('schedule_type'); // Custom cron expression
            $table->timestamp('last_checked_at')->nullable()->after('cron_expression');
            $table->timestamp('next_check_at')->nullable()->after('last_checked_at');
            $table->boolean('use_queue')->default(true)->after('next_check_at'); // Use job queue instead of direct execution
            $table->integer('priority')->default(1)->after('use_queue'); // Queue priority (1-10, higher = more priority)
            $table->json('schedule_config')->nullable()->after('priority'); // Additional scheduling configuration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'schedule_type',
                'cron_expression', 
                'last_checked_at',
                'next_check_at',
                'use_queue',
                'priority',
                'schedule_config'
            ]);
        });
    }
};
