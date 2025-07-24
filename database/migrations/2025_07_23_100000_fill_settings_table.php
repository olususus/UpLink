<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fill the settings table with default values
        $defaults = [
            ['key' => 'site_name', 'value' => 'DBusWorld Status'],
            ['key' => 'company_name', 'value' => 'DBusWorld'],
            ['key' => 'support_email', 'value' => 'support@dbusworld.com'],
            ['key' => 'support_url', 'value' => 'https://dbusworld.com/support'],
            ['key' => 'twitter_handle', 'value' => '@dbusworld'],
            ['key' => 'primary_color', 'value' => '#3b82f6'],
            ['key' => 'success_color', 'value' => '#10b981'],
            ['key' => 'warning_color', 'value' => '#f59e0b'],
            ['key' => 'danger_color', 'value' => '#ef4444'],
            ['key' => 'info_color', 'value' => '#06b6d4'],
            ['key' => 'default_check_interval', 'value' => '300'],
            ['key' => 'default_timeout', 'value' => '10'],
            ['key' => 'min_check_interval', 'value' => '60'],
            ['key' => 'max_check_interval', 'value' => '3600'],
            ['key' => 'show_uptime_percentage', 'value' => '1'],
            ['key' => 'show_response_times', 'value' => '1'],
            ['key' => 'uptime_calculation_days', 'value' => '30'],
            ['key' => 'incident_retention_days', 'value' => '90'],
            ['key' => 'dark_mode_enabled', 'value' => '1'],
            ['key' => 'default_dark_mode', 'value' => '0'],
            ['key' => 'email_enabled', 'value' => '0'],
            ['key' => 'slack_enabled', 'value' => '0'],
            ['key' => 'discord_enabled', 'value' => '0'],
            ['key' => 'discord_webhook_url', 'value' => ''],
            ['key' => 'enable_auto_refresh', 'value' => '1'],
            ['key' => 'auto_refresh_interval', 'value' => '30'],
        ];
        foreach ($defaults as $row) {
            DB::table('settings')->updateOrInsert(['key' => $row['key']], ['value' => $row['value']]);
        }
    }

    public function down(): void
    {
        // Optionally, remove the inserted settings
        $keys = [
            'site_name', 'company_name', 'support_email', 'support_url', 'twitter_handle',
            'primary_color', 'success_color', 'warning_color', 'danger_color', 'info_color',
            'default_check_interval', 'default_timeout', 'min_check_interval', 'max_check_interval',
            'show_uptime_percentage', 'show_response_times', 'uptime_calculation_days', 'incident_retention_days',
            'dark_mode_enabled', 'default_dark_mode', 'email_enabled', 'slack_enabled', 'discord_enabled',
            'discord_webhook_url', 'enable_auto_refresh', 'auto_refresh_interval',
        ];
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
