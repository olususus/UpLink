@extends('admin.layout')

@section('title', 'Advanced Service Configuration')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Advanced Service Configuration</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-400 dark:hover:bg-gray-700 transition-colors duration-200">
                    Simple Create
                </a>
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
                    Back to Services
                </a>
            </div>
        </div>

        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">Advanced Monitoring Features</h3>
            <p class="text-blue-700 dark:text-blue-300 text-sm">Configure comprehensive monitoring with content validation, SSL checks, performance thresholds, geographic monitoring, and more.</p>
        </div>

        <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-8">
            @csrf

            <!-- Basic Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Basic Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL to Monitor</label>
                        <input type="url" name="url" id="url" value="{{ old('url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>
            </div>

            <!-- HTTP Configuration -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">HTTP Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timeout (seconds)</label>
                        <input type="number" name="timeout" id="timeout" value="{{ old('timeout', 10) }}" min="1" max="60"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="expected_status_codes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Status Codes</label>
                        <input type="text" name="expected_status_codes" id="expected_status_codes" value="{{ old('expected_status_codes', '200-299') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Examples: 200, 200-299, 200,201,202</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="http_headers" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom HTTP Headers (JSON)</label>
                    <textarea name="http_headers" id="http_headers" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('http_headers') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "User-Agent": "StatusMonitor/1.0",
  "Accept": "application/json",
  "Authorization": "Bearer your-token"
}</pre>
                    </div>
                </div>
            </div>

            <!-- Content Validation -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Content Validation</h2>
                <div>
                    <label for="content_checks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content Checks (JSON)</label>
                    <textarea name="content_checks" id="content_checks" rows="6" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content_checks') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "required_text": ["Welcome", "Login", "Dashboard"],
  "forbidden_text": ["Error 500", "Database Error", "Maintenance"],
  "required_elements": [
    {"selector": "#main-navigation", "count": 1},
    {"selector": ".user-menu", "count": 1}
  ],
  "json_validation": {
    "path": "/api/health",
    "required_fields": ["status", "version", "uptime"]
  },
  "response_size": {
    "min_bytes": 1000,
    "max_bytes": 50000
  }
}</pre>
                    </div>
                </div>
            </div>

            <!-- SSL & Security Monitoring -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">SSL & Security Monitoring</h2>
                <div>
                    <label for="ssl_monitoring" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SSL Configuration (JSON)</label>
                    <textarea name="ssl_monitoring" id="ssl_monitoring" rows="5" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('ssl_monitoring') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "enabled": true,
  "check_expiry": true,
  "expiry_warning_days": 30,
  "verify_certificate": true,
  "check_revocation": true,
  "required_cipher_suites": ["TLS_AES_256_GCM_SHA384"]
}</pre>
                    </div>
                </div>
            </div>

            <!-- DNS & Network Monitoring -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">DNS & Network Monitoring</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dns_monitoring" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNS Configuration (JSON)</label>
                        <textarea name="dns_monitoring" id="dns_monitoring" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('dns_monitoring') }}</textarea>
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                            <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                            <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "enabled": true,
  "expected_ips": ["1.2.3.4", "5.6.7.8"],
  "dns_servers": ["8.8.8.8", "1.1.1.1"],
  "check_mx_records": true,
  "response_time_threshold": 500
}</pre>
                        </div>
                    </div>
                    <div>
                        <label for="port_monitoring" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Port Monitoring (JSON)</label>
                        <textarea name="port_monitoring" id="port_monitoring" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('port_monitoring') }}</textarea>
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                            <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                            <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "enabled": true,
  "ports": [
    {"number": 80, "protocol": "tcp"},
    {"number": 443, "protocol": "tcp"},
    {"number": 22, "protocol": "tcp"}
  ],
  "timeout": 5
}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Thresholds -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Performance Thresholds</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="response_time_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Response Time Threshold (ms)</label>
                        <input type="number" name="response_time_threshold" id="response_time_threshold" value="{{ old('response_time_threshold', 5000) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="performance_thresholds" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Advanced Performance (JSON)</label>
                        <textarea name="performance_thresholds" id="performance_thresholds" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('performance_thresholds') }}</textarea>
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                            <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                            <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "dns_lookup": 100,
  "tcp_connect": 200,
  "ssl_handshake": 500,
  "first_byte": 1000,
  "total_time": 3000
}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geographic Monitoring -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Geographic Monitoring</h2>
                <div>
                    <label for="monitoring_regions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monitoring Regions (JSON)</label>
                    <textarea name="monitoring_regions" id="monitoring_regions" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('monitoring_regions') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "enabled": true,
  "regions": ["us-east", "us-west", "eu-central", "asia-pacific"],
  "require_all_pass": false,
  "failure_threshold_percentage": 50
}</pre>
                    </div>
                </div>
            </div>

            <!-- Authentication -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Authentication</h2>
                <div>
                    <label for="auth_config" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Authentication Configuration (JSON)</label>
                    <textarea name="auth_config" id="auth_config" rows="6" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('auth_config') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Examples:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">// Basic Auth
{
  "type": "basic",
  "username": "monitor_user",
  "password": "secure_password"
}

// Bearer Token
{
  "type": "bearer",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}

// API Key
{
  "type": "api_key",
  "key": "X-API-Key",
  "value": "your-api-key-here"
}</pre>
                    </div>
                </div>
            </div>

            <!-- Retry & Failure Handling -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Retry & Failure Handling</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="retry_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Retry Attempts</label>
                        <input type="number" name="retry_attempts" id="retry_attempts" value="{{ old('retry_attempts', 3) }}" min="0" max="10"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="retry_delay" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Retry Delay (seconds)</label>
                        <input type="number" name="retry_delay" id="retry_delay" value="{{ old('retry_delay', 5) }}" min="1" max="60"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="consecutive_failures_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Failure Threshold</label>
                        <input type="number" name="consecutive_failures_threshold" id="consecutive_failures_threshold" value="{{ old('consecutive_failures_threshold', 3) }}" min="1" max="10"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Custom Scripts -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Custom Scripts & Webhooks</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="custom_scripts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom Scripts (JSON)</label>
                        <textarea name="custom_scripts" id="custom_scripts" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('custom_scripts') }}</textarea>
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                            <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                            <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "before_check": "curl -X POST /api/pre-check",
  "after_check": "curl -X POST /api/post-check",
  "on_failure": "curl -X POST /api/failure-hook"
}</pre>
                        </div>
                    </div>
                    <div>
                        <label for="webhook_config" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Webhook Configuration (JSON)</label>
                        <textarea name="webhook_config" id="webhook_config" rows="5" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('webhook_config') }}</textarea>
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                            <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                            <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "status_change": "https://api.example.com/webhook",
  "method": "POST",
  "headers": {"Authorization": "Bearer token"},
  "timeout": 10
}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Windows -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Maintenance Windows</h2>
                <div>
                    <label for="maintenance_windows" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Schedule (JSON)</label>
                    <textarea name="maintenance_windows" id="maintenance_windows" rows="5" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('maintenance_windows') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "weekly": [
    {
      "day": "sunday", 
      "start": "02:00", 
      "end": "04:00",
      "timezone": "UTC"
    }
  ],
  "one_time": [
    {
      "start": "2025-07-25T02:00:00Z",
      "end": "2025-07-25T06:00:00Z",
      "description": "Server migration"
    }
  ]
}</pre>
                    </div>
                </div>
            </div>

            <!-- Alert Escalation -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Alert Escalation</h2>
                <div>
                    <label for="alert_escalation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escalation Rules (JSON)</label>
                    <textarea name="alert_escalation" id="alert_escalation" rows="6" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('alert_escalation') }}</textarea>
                    <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-600 rounded text-sm">
                        <strong class="text-gray-900 dark:text-gray-100">Example:</strong>
                        <pre class="text-gray-700 dark:text-gray-300 mt-1">{
  "levels": [
    {
      "delay_minutes": 0,
      "channels": ["discord", "email"],
      "recipients": ["team@company.com"]
    },
    {
      "delay_minutes": 15,
      "channels": ["sms", "phone"],
      "recipients": ["manager@company.com"]
    },
    {
      "delay_minutes": 60,
      "channels": ["pagerduty"],
      "recipients": ["oncall@company.com"]
    }
  ]
}</pre>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors duration-300">
                    Create Advanced Service
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slug = this.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});

// JSON validation helpers
function validateJSON(textareaId) {
    const textarea = document.getElementById(textareaId);
    const value = textarea.value.trim();
    
    if (!value) return true;
    
    try {
        JSON.parse(value);
        textarea.classList.remove('border-red-500');
        textarea.classList.add('border-green-500');
        return true;
    } catch (e) {
        textarea.classList.remove('border-green-500');
        textarea.classList.add('border-red-500');
        return false;
    }
}

// Add JSON validation to all JSON textareas
const jsonFields = [
    'http_headers', 'content_checks', 'ssl_monitoring', 'dns_monitoring',
    'port_monitoring', 'performance_thresholds', 'monitoring_regions',
    'auth_config', 'custom_scripts', 'webhook_config', 'maintenance_windows',
    'alert_escalation', 'notification_preferences'
];

jsonFields.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (field) {
        field.addEventListener('blur', () => validateJSON(fieldId));
    }
});
</script>
</div>
@endsection
