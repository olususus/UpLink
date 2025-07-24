<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DiscordNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NotificationController extends Controller
{
    public function index()
    {
        $keys = [
            'email_enabled', 'slack_enabled', 'discord_enabled', 'discord_webhook_url',
            'notification_email', 'support_email'
        ];
        $settings = \App\Models\Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        // Ensure booleans are cast
        $settings['email_enabled'] = ($settings['email_enabled'] ?? '0') == '1';
        $settings['slack_enabled'] = ($settings['slack_enabled'] ?? '0') == '1';
        $settings['discord_enabled'] = ($settings['discord_enabled'] ?? '0') == '1';
        return view('admin.notifications.index', compact('settings'));
    }

    public function testDiscord()
    {
        if (!config('status.notifications.discord_enabled', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Discord notifications are not enabled'
            ]);
        }

        if (!config('status.notifications.discord_webhook_url')) {
            return response()->json([
                'success' => false,
                'message' => 'Discord webhook URL is not configured'
            ]);
        }

        $discordService = new DiscordNotificationService();
        $result = $discordService->testConnection();

        return response()->json($result);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'discord_enabled' => 'boolean',
            'discord_webhook_url' => 'nullable|url',
            'email_enabled' => 'boolean',
            'notification_email' => 'nullable|email',
        ]);

        return back()->with('success', 'Notification settings would be updated. Configure these in your .env file:
        
NOTIFICATIONS_DISCORD_ENABLED=' . ($request->discord_enabled ? 'true' : 'false') . '
DISCORD_WEBHOOK_URL=' . ($request->discord_webhook_url ?: 'your_webhook_url_here') . '
NOTIFICATIONS_EMAIL_ENABLED=' . ($request->email_enabled ? 'true' : 'false') . '
NOTIFICATION_EMAIL=' . ($request->notification_email ?: 'your_email_here'));
    }
}
