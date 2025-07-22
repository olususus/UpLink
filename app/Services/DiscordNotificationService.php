<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordNotificationService
{
    private ?string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('status.notifications.discord_webhook_url');
    }

    public function sendStatusChangeNotification(Service $service, string $previousStatus, string $currentStatus): bool
    {
        if (!$this->webhookUrl || !config('status.notifications.discord_enabled', false)) {
            return false;
        }

        try {
            $embed = $this->buildStatusChangeEmbed($service, $previousStatus, $currentStatus);
            
            $payload = [
                'username' => config('status.company_name', 'Status Monitor'),
                'avatar_url' => $this->getAvatarUrl($currentStatus),
                'embeds' => [$embed]
            ];

            $response = Http::timeout(10)->post($this->webhookUrl, $payload);

            if ($response->successful()) {
                Log::info("Discord notification sent for {$service->name} status change: {$previousStatus} → {$currentStatus}");
                return true;
            } else {
                Log::error("Discord notification failed for {$service->name}: HTTP {$response->status()}");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Discord notification error for {$service->name}: " . $e->getMessage());
            return false;
        }
    }

    public function sendIncidentNotification(string $title, string $description, string $status, ?Service $service = null): bool
    {
        if (!$this->webhookUrl || !config('status.notifications.discord_enabled', false)) {
            return false;
        }

        try {
            $embed = $this->buildIncidentEmbed($title, $description, $status, $service);
            
            $payload = [
                'username' => config('status.company_name', 'Status Monitor'),
                'avatar_url' => $this->getAvatarUrl('incident'),
                'embeds' => [$embed]
            ];

            $response = Http::timeout(10)->post($this->webhookUrl, $payload);

            if ($response->successful()) {
                Log::info("Discord incident notification sent: {$title}");
                return true;
            } else {
                Log::error("Discord incident notification failed: HTTP {$response->status()}");
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Discord incident notification error: " . $e->getMessage());
            return false;
        }
    }

    private function buildStatusChangeEmbed(Service $service, string $previousStatus, string $currentStatus): array
    {
        $color = $this->getStatusColor($currentStatus);
        $emoji = $this->getStatusEmoji($currentStatus);
        
        $embed = [
            'title' => "{$emoji} Service Status Update",
            'description' => "**{$service->name}** status has changed",
            'color' => $color,
            'fields' => [
                [
                    'name' => '📊 Previous Status',
                    'value' => $this->formatStatus($previousStatus),
                    'inline' => true
                ],
                [
                    'name' => '🔄 Current Status', 
                    'value' => $this->formatStatus($currentStatus),
                    'inline' => true
                ],
                [
                    'name' => '🔗 Service URL',
                    'value' => $service->url ?: 'Not specified',
                    'inline' => false
                ]
            ],
            'timestamp' => now()->toISOString(),
            'footer' => [
                'text' => config('status.company_name', 'Status Monitor'),
                'icon_url' => $this->getFooterIconUrl()
            ]
        ];

        // Add status message if available
        if ($service->status_message) {
            $embed['fields'][] = [
                'name' => '💬 Status Message',
                'value' => $service->status_message,
                'inline' => false
            ];
        }

        // Add service description if available
        if ($service->description) {
            $embed['fields'][] = [
                'name' => '📝 Service Description',
                'value' => $service->description,
                'inline' => false
            ];
        }

        return $embed;
    }

    private function buildIncidentEmbed(string $title, string $description, string $status, ?Service $service = null): array
    {
        $color = $this->getIncidentColor($status);
        $emoji = $this->getIncidentEmoji($status);
        
        $embed = [
            'title' => "{$emoji} Incident Report",
            'description' => "**{$title}**",
            'color' => $color,
            'fields' => [
                [
                    'name' => '📋 Description',
                    'value' => $description ?: 'No additional details provided',
                    'inline' => false
                ],
                [
                    'name' => '🏷️ Status',
                    'value' => $this->formatIncidentStatus($status),
                    'inline' => true
                ]
            ],
            'timestamp' => now()->toISOString(),
            'footer' => [
                'text' => config('status.company_name', 'Status Monitor'),
                'icon_url' => $this->getFooterIconUrl()
            ]
        ];

        // Add affected service if specified
        if ($service) {
            $embed['fields'][] = [
                'name' => '🎯 Affected Service',
                'value' => $service->name,
                'inline' => true
            ];
        }

        // Add status page link
        $embed['fields'][] = [
            'name' => '🔗 Status Page',
            'value' => '[View Full Status](' . url('/') . ')',
            'inline' => false
        ];

        return $embed;
    }

    private function getStatusColor(string $status): int
    {
        return match($status) {
            'operational' => 0x10b981,
            'degraded' => 0xf59e0b,
            'maintenance' => 0x3b82f6,
            'outage' => 0xef4444,
            default => 0x6b7280
        };
    }

    private function getIncidentColor(string $status): int
    {
        return match($status) {
            'investigating' => 0xf59e0b,
            'identified' => 0xef4444,
            'monitoring' => 0x3b82f6,
            'resolved' => 0x10b981,
            default => 0x6b7280
        };
    }

    private function getStatusEmoji(string $status): string
    {
        return match($status) {
            'operational' => '✅',
            'degraded' => '⚠️',
            'maintenance' => '🔧',
            'outage' => '🚨',
            default => '❓'
        };
    }

    private function getIncidentEmoji(string $status): string
    {
        return match($status) {
            'investigating' => '🔍',
            'identified' => '⚠️',
            'monitoring' => '👀',
            'resolved' => '✅',
            default => '📋'
        };
    }

    private function formatStatus(string $status): string
    {
        $emoji = $this->getStatusEmoji($status);
        $text = ucfirst($status);
        
        return "{$emoji} **{$text}**";
    }

    private function formatIncidentStatus(string $status): string
    {
        $emoji = $this->getIncidentEmoji($status);
        $text = ucfirst($status);
        
        return "{$emoji} **{$text}**";
    }

    private function getAvatarUrl(string $status): string
    {
        return match($status) {
            'operational' => 'https://cdn.discordapp.com/emojis/887396392013168662.png',
            'degraded' => 'https://cdn.discordapp.com/emojis/887396392017362944.png',
            'maintenance' => 'https://cdn.discordapp.com/emojis/887396392021557248.png',
            'outage' => 'https://cdn.discordapp.com/emojis/887396392025751552.png',
            'incident' => 'https://cdn.discordapp.com/emojis/887396392030945792.png',
            default => 'https://cdn.discordapp.com/emojis/887396392035139584.png'
        };
    }

    private function getFooterIconUrl(): string
    {
        return 'https://cdn.discordapp.com/emojis/887396392039333888.png';
    }

    public function testConnection(): array
    {
        if (!$this->webhookUrl) {
            return [
                'success' => false,
                'message' => 'Discord webhook URL not configured'
            ];
        }

        try {
            $embed = [
                'title' => '🧪 Discord Integration Test',
                'description' => 'This is a test message to verify Discord webhook integration.',
                'color' => 0x3b82f6, // Blue
                'fields' => [
                    [
                        'name' => '✅ Connection Status',
                        'value' => 'Successfully connected to Discord',
                        'inline' => false
                    ],
                    [
                        'name' => '🕐 Test Time',
                        'value' => now()->format('Y-m-d H:i:s T'),
                        'inline' => true
                    ]
                ],
                'timestamp' => now()->toISOString(),
                'footer' => [
                    'text' => config('status.company_name', 'Status Monitor') . ' - Test Message',
                    'icon_url' => $this->getFooterIconUrl()
                ]
            ];

            $payload = [
                'username' => config('status.company_name', 'Status Monitor'),
                'avatar_url' => $this->getAvatarUrl('operational'),
                'embeds' => [$embed]
            ];

            $response = Http::timeout(10)->post($this->webhookUrl, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Discord webhook test successful! Check your Discord channel.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "Discord webhook test failed: HTTP {$response->status()}"
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Discord webhook test failed: ' . $e->getMessage()
            ];
        }
    }
}
