<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Status Page Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration options allow you to customize the appearance
    | and behavior of your status monitoring application.
    |
    */

    'app_name' => env('APP_NAME', 'Status Monitor'),
    
    'company_name' => env('COMPANY_NAME', 'Your Company'),
    
    'support_email' => env('SUPPORT_EMAIL', 'support@yourcompany.com'),
    
    'support_url' => env('SUPPORT_URL', null),
    
    'twitter_handle' => env('TWITTER_HANDLE', null),
    
    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    */
    
    'default_check_interval' => env('DEFAULT_CHECK_INTERVAL', 300), // 5 minutes
    
    'default_timeout' => env('DEFAULT_TIMEOUT', 10),
    
    'max_check_interval' => env('MAX_CHECK_INTERVAL', 3600), // 1 hour
    
    'min_check_interval' => env('MIN_CHECK_INTERVAL', 60), // 1 minute
    
    /*
    |--------------------------------------------------------------------------
    | Status Page Display Options
    |--------------------------------------------------------------------------
    */
    
    'show_uptime_percentage' => env('SHOW_UPTIME_PERCENTAGE', true),
    
    'show_response_times' => env('SHOW_RESPONSE_TIMES', true),
    
    'uptime_calculation_days' => env('UPTIME_CALCULATION_DAYS', 30),
    
    'incident_retention_days' => env('INCIDENT_RETENTION_DAYS', 90),
    
    /*
    |--------------------------------------------------------------------------
    | Theme & Appearance
    |--------------------------------------------------------------------------
    */
    
    'theme' => [
        'primary_color' => env('THEME_PRIMARY_COLOR', '#3b82f6'), // blue-500
        'success_color' => env('THEME_SUCCESS_COLOR', '#10b981'), // green-500
        'warning_color' => env('THEME_WARNING_COLOR', '#f59e0b'), // yellow-500
        'danger_color' => env('THEME_DANGER_COLOR', '#ef4444'), // red-500
        'info_color' => env('THEME_INFO_COLOR', '#06b6d4'), // cyan-500
        'dark_mode_enabled' => env('DARK_MODE_ENABLED', true),
        'default_dark_mode' => env('DEFAULT_DARK_MODE', false),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    
    'notifications' => [
        'email_enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', false),
        'slack_enabled' => env('NOTIFICATIONS_SLACK_ENABLED', false),
        'discord_enabled' => env('NOTIFICATIONS_DISCORD_ENABLED', false),
        'discord_webhook_url' => env('DISCORD_WEBHOOK_URL', null),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Auto-refresh Settings
    |--------------------------------------------------------------------------
    */
    
    'auto_refresh_interval' => env('AUTO_REFRESH_INTERVAL', 30), // seconds
    
    'enable_auto_refresh' => env('ENABLE_AUTO_REFRESH', true),
];
