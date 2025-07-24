<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{

    public function edit()
    {
        $keys = [
            'logo', 'site_name', 'company_name', 'support_email', 'support_url', 'twitter_handle',
            'primary_color', 'success_color', 'warning_color', 'danger_color', 'info_color',
            'default_check_interval', 'default_timeout', 'min_check_interval', 'max_check_interval',
            'show_uptime_percentage', 'show_response_times', 'uptime_calculation_days', 'incident_retention_days',
            'dark_mode_enabled', 'default_dark_mode',
            'email_enabled', 'slack_enabled', 'discord_enabled', 'discord_webhook_url',
            'enable_auto_refresh', 'auto_refresh_interval',
        ];
        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');
        return view('admin.settings.edit', [
            'logo' => $settings['logo'] ?? null,
            'site_name' => $settings['site_name'] ?? null,
            'company_name' => $settings['company_name'] ?? null,
            'support_email' => $settings['support_email'] ?? null,
            'support_url' => $settings['support_url'] ?? null,
            'twitter_handle' => $settings['twitter_handle'] ?? null,
            'primary_color' => $settings['primary_color'] ?? '#3b82f6',
            'success_color' => $settings['success_color'] ?? '#10b981',
            'warning_color' => $settings['warning_color'] ?? '#f59e0b',
            'danger_color' => $settings['danger_color'] ?? '#ef4444',
            'info_color' => $settings['info_color'] ?? '#06b6d4',
            'default_check_interval' => $settings['default_check_interval'] ?? 300,
            'default_timeout' => $settings['default_timeout'] ?? 10,
            'min_check_interval' => $settings['min_check_interval'] ?? 60,
            'max_check_interval' => $settings['max_check_interval'] ?? 3600,
            'show_uptime_percentage' => ($settings['show_uptime_percentage'] ?? '1') == '1',
            'show_response_times' => ($settings['show_response_times'] ?? '1') == '1',
            'uptime_calculation_days' => $settings['uptime_calculation_days'] ?? 30,
            'incident_retention_days' => $settings['incident_retention_days'] ?? 90,
            'dark_mode_enabled' => ($settings['dark_mode_enabled'] ?? '1') == '1',
            'default_dark_mode' => ($settings['default_dark_mode'] ?? '0') == '1',
            'email_enabled' => ($settings['email_enabled'] ?? '0') == '1',
            'slack_enabled' => ($settings['slack_enabled'] ?? '0') == '1',
            'discord_enabled' => ($settings['discord_enabled'] ?? '0') == '1',
            'discord_webhook_url' => $settings['discord_webhook_url'] ?? '',
            'enable_auto_refresh' => ($settings['enable_auto_refresh'] ?? '1') == '1',
            'auto_refresh_interval' => $settings['auto_refresh_interval'] ?? 30,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_url' => 'nullable|url|max:255',
            'twitter_handle' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:16',
            'success_color' => 'nullable|string|max:16',
            'warning_color' => 'nullable|string|max:16',
            'danger_color' => 'nullable|string|max:16',
            'info_color' => 'nullable|string|max:16',
            'default_check_interval' => 'nullable|integer|min:1',
            'default_timeout' => 'nullable|integer|min:1',
            'min_check_interval' => 'nullable|integer|min:1',
            'max_check_interval' => 'nullable|integer|min:1',
            'uptime_calculation_days' => 'nullable|integer|min:1',
            'incident_retention_days' => 'nullable|integer|min:1',
            'discord_webhook_url' => 'nullable|url|max:255',
            'auto_refresh_interval' => 'nullable|integer|min:1',
            'logo' => 'nullable|image|max:2048',
        ]);

        $fields = [
            'site_name', 'company_name', 'support_email', 'support_url', 'twitter_handle',
            'primary_color', 'success_color', 'warning_color', 'danger_color', 'info_color',
            'default_check_interval', 'default_timeout', 'min_check_interval', 'max_check_interval',
            'uptime_calculation_days', 'incident_retention_days',
            'discord_webhook_url', 'auto_refresh_interval',
        ];
        foreach ($fields as $field) {
            Setting::updateOrCreate(['key' => $field], ['value' => $request->input($field)]);
        }

        // Boolean/checkbox fields
        $bools = [
            'show_uptime_percentage', 'show_response_times', 'dark_mode_enabled', 'default_dark_mode',
            'email_enabled', 'slack_enabled', 'discord_enabled', 'enable_auto_refresh',
        ];
        foreach ($bools as $bool) {
            Setting::updateOrCreate(['key' => $bool], ['value' => $request->has($bool) ? '1' : '0']);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'logo'], ['value' => $path]);
        }

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated!');
    }
}
