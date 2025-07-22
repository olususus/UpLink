@extends('admin.layout')

@section('title', 'Notification Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Notification Settings</h1>
        <p class="text-gray-600">Configure email and Discord notifications for service status changes</p>
    </div>

    <!-- Current Settings Overview -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Current Configuration</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Active notification channels</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email Notifications</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settings['email_enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $settings['email_enabled'] ? 'Enabled' : 'Disabled' }}
                        </span>
                        @if($settings['notification_email'])
                            <span class="ml-2 text-gray-600">→ {{ $settings['notification_email'] }}</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Discord Notifications</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settings['discord_enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $settings['discord_enabled'] ? 'Enabled' : 'Disabled' }}
                        </span>
                        @if($settings['discord_webhook_url'])
                            <span class="ml-2 text-gray-600">→ Webhook configured</span>
                            <button onclick="testDiscordWebhook()" class="ml-2 inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:bg-blue-200">
                                Test Connection
                            </button>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Discord Setup Guide -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Discord Webhook Setup</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p class="mb-2">To set up Discord notifications:</p>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Go to your Discord server settings</li>
                        <li>Navigate to Integrations → Webhooks</li>
                        <li>Click "Create Webhook" or "New Webhook"</li>
                        <li>Choose the channel where you want notifications</li>
                        <li>Copy the webhook URL</li>
                        <li>Add it to your .env file as <code class="bg-blue-100 px-1 rounded">DISCORD_WEBHOOK_URL</code></li>
                        <li>Set <code class="bg-blue-100 px-1 rounded">NOTIFICATIONS_DISCORD_ENABLED=true</code></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Discord Notification -->
    @if($settings['discord_enabled'] && $settings['discord_webhook_url'])
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Test Discord Integration</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Send a test message to verify your Discord webhook is working</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <button onclick="testDiscordWebhook()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Test Message
            </button>
            <div id="testResult" class="mt-3 hidden">
                <div id="testMessage" class="text-sm"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Environment Configuration Example -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Environment Configuration</h3>
        <p class="text-sm text-gray-600 mb-4">Add these settings to your <code class="bg-gray-200 px-1 rounded">.env</code> file:</p>
        
        <div class="bg-gray-900 rounded-md p-4 overflow-x-auto">
            <pre class="text-green-400 text-sm"><code># Discord Notifications
NOTIFICATIONS_DISCORD_ENABLED={{ $settings['discord_enabled'] ? 'true' : 'false' }}
DISCORD_WEBHOOK_URL={{ $settings['discord_webhook_url'] ?: 'https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN' }}

# Email Notifications  
NOTIFICATIONS_EMAIL_ENABLED={{ $settings['email_enabled'] ? 'true' : 'false' }}
NOTIFICATION_EMAIL={{ $settings['notification_email'] ?: 'alerts@yourcompany.com' }}

# Company Branding (appears in Discord messages)
COMPANY_NAME="{{ config('status.company_name', 'Your Company') }}"</code></pre>
        </div>
        
        <div class="mt-4 text-sm text-gray-600">
            <p><strong>Note:</strong> After updating your .env file, run <code class="bg-gray-200 px-1 rounded">php artisan config:cache</code> to apply changes.</p>
        </div>
    </div>

    <!-- Example Discord Message -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Discord Message Preview</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">This is how status change notifications will appear in Discord</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="bg-gray-100 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr(config('status.company_name', 'SM'), 0, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-medium text-gray-900">{{ config('status.company_name', 'Status Monitor') }}</span>
                            <span class="text-xs text-gray-500">Today at 12:34 PM</span>
                        </div>
                        <div class="mt-2 bg-white border-l-4 border-red-500 rounded p-3">
                            <div class="flex items-center mb-2">
                                <span class="text-lg mr-2">🚨</span>
                                <span class="font-semibold text-gray-900">Service Status Update</span>
                            </div>
                            <p class="text-gray-700 mb-3"><strong>Main Website</strong> status has changed</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">📊 Previous Status</span>
                                    <span class="font-medium">✅ <strong>Operational</strong></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">🔄 Current Status</span>
                                    <span class="font-medium">🚨 <strong>Outage</strong></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">🔗 Service URL</span>
                                    <span class="text-blue-600">https://yoursite.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function testDiscordWebhook() {
    const button = event.target;
    const resultDiv = document.getElementById('testResult');
    const messageDiv = document.getElementById('testMessage');
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Testing...';
    
    try {
        const response = await fetch('{{ route("admin.notifications.test-discord") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        resultDiv.classList.remove('hidden');
        
        if (data.success) {
            messageDiv.innerHTML = '<div class="flex items-center text-green-600"><svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' + data.message + '</div>';
        } else {
            messageDiv.innerHTML = '<div class="flex items-center text-red-600"><svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>' + data.message + '</div>';
        }
        
    } catch (error) {
        resultDiv.classList.remove('hidden');
        messageDiv.innerHTML = '<div class="flex items-center text-red-600"><svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>Connection failed: ' + error.message + '</div>';
    } finally {
        // Restore button
        button.disabled = false;
        button.innerHTML = '<svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>Send Test Message';
    }
}
</script>
@endsection
