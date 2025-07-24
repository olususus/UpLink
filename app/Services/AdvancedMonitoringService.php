<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdvancedMonitoringService
{
    /**
     * Perform comprehensive monitoring check for a service
     */
    public function performCheck(Service $service): array
    {
        $result = [
            'status' => 'operational',
            'response_time' => 0,
            'checks_performed' => [],
            'errors' => [],
            'metadata' => []
        ];

        try {
            // Skip check if in maintenance window
            if ($this->isInMaintenanceWindow($service)) {
                $result['status'] = 'maintenance';
                $result['checks_performed'][] = 'maintenance_window_check';
                return $result;
            }

            // Perform HTTP check if URL provided
            if ($service->url && filter_var($service->url, FILTER_VALIDATE_URL)) {
                $httpResult = $this->performHttpCheck($service);
                $result = array_merge($result, $httpResult);
            }

            // Perform port monitoring
            if ($service->port_monitoring && $service->port_monitoring['enabled'] ?? false) {
                $portResult = $this->performPortCheck($service);
                $result['checks_performed'][] = 'port_monitoring';
                if (!empty($portResult['errors'])) {
                    $result['errors'] = array_merge($result['errors'], $portResult['errors']);
                    $result['status'] = 'outage';
                }
            }

            // Perform DNS monitoring
            if ($service->dns_monitoring && $service->dns_monitoring['enabled'] ?? false) {
                $dnsResult = $this->performDnsCheck($service);
                $result['checks_performed'][] = 'dns_monitoring';
                if (!empty($dnsResult['errors'])) {
                    $result['errors'] = array_merge($result['errors'], $dnsResult['errors']);
                }
            }

            // SSL certificate monitoring
            if ($service->ssl_monitoring && $service->ssl_monitoring['enabled'] ?? false) {
                $sslResult = $this->performSslCheck($service);
                $result['checks_performed'][] = 'ssl_monitoring';
                if (!empty($sslResult['errors'])) {
                    $result['errors'] = array_merge($result['errors'], $sslResult['errors']);
                }
            }

            // Execute custom scripts
            if ($service->custom_scripts) {
                $scriptResult = $this->executeCustomScripts($service);
                $result['checks_performed'][] = 'custom_scripts';
                if (!empty($scriptResult['errors'])) {
                    $result['errors'] = array_merge($result['errors'], $scriptResult['errors']);
                }
            }

            // Determine final status
            if (!empty($result['errors'])) {
                $result['status'] = count($result['errors']) > 2 ? 'outage' : 'degraded';
            }

        } catch (\Exception $e) {
            $result['status'] = 'outage';
            $result['errors'][] = 'Exception: ' . $e->getMessage();
            Log::error('Advanced monitoring check failed', [
                'service_id' => $service->id,
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Perform HTTP monitoring with advanced content checks
     */
    protected function performHttpCheck(Service $service): array
    {
        $result = [
            'checks_performed' => ['http_request'],
            'errors' => [],
            'metadata' => []
        ];

        $startTime = microtime(true);
        
        // Build HTTP client with custom configuration
        $client = Http::timeout($service->timeout ?? 10);
        
        // Disable SSL verification for development (must be done before other options)
        $options = [
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]
        ];
        
        // Configure redirects
        if (!($service->follow_redirects ?? true)) {
            $options['allow_redirects'] = false;
        }
        
        $client = $client->withOptions($options);
        
        // Add custom headers
        if ($service->http_headers) {
            foreach ($service->http_headers as $key => $value) {
                $client = $client->withHeaders([$key => $value]);
            }
        }

        // Add authentication
        if ($service->auth_config) {
            $client = $this->addAuthentication($client, $service->auth_config);
        }

        try {
            $response = $client->get($service->url);
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);
            
            $result['response_time'] = $responseTime;
            $result['metadata']['status_code'] = $response->status();
            $result['metadata']['response_size'] = strlen($response->body());

            // Check response time threshold
            if ($responseTime > ($service->response_time_threshold ?? 5000)) {
                $result['errors'][] = "Response time {$responseTime}ms exceeds threshold";
            }

            // Check status codes
            if (!$this->isValidStatusCode($response->status(), $service->expected_status_codes)) {
                $result['errors'][] = "Unexpected status code: {$response->status()}";
            }

            // Perform content checks
            if ($service->content_checks) {
                $contentResult = $this->performContentChecks($response, $service->content_checks);
                $result['checks_performed'][] = 'content_validation';
                $result['errors'] = array_merge($result['errors'], $contentResult['errors']);
            }

            // Check performance thresholds
            if ($service->performance_thresholds) {
                $perfResult = $this->checkPerformanceThresholds($response, $service->performance_thresholds);
                $result['checks_performed'][] = 'performance_thresholds';
                $result['errors'] = array_merge($result['errors'], $perfResult['errors']);
            }

        } catch (\Exception $e) {
            $result['errors'][] = 'HTTP request failed: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Add authentication to HTTP client
     */
    protected function addAuthentication($client, array $authConfig)
    {
        switch ($authConfig['type'] ?? 'none') {
            case 'basic':
                return $client->withBasicAuth($authConfig['username'], $authConfig['password']);
            
            case 'bearer':
                return $client->withToken($authConfig['token']);
            
            case 'api_key':
                return $client->withHeaders([$authConfig['key'] => $authConfig['value']]);
            
            default:
                return $client;
        }
    }

    /**
     * Validate HTTP status code
     */
    protected function isValidStatusCode(int $statusCode, ?string $expectedCodes): bool
    {
        if (!$expectedCodes) {
            $expectedCodes = '200-299';
        }

        $codes = explode(',', $expectedCodes);
        
        foreach ($codes as $code) {
            $code = trim($code);
            
            if (str_contains($code, '-')) {
                [$min, $max] = explode('-', $code, 2);
                if ($statusCode >= (int)$min && $statusCode <= (int)$max) {
                    return true;
                }
            } else {
                if ($statusCode == (int)$code) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Perform content validation checks
     */
    protected function performContentChecks($response, array $contentChecks): array
    {
        $errors = [];
        $body = $response->body();

        // Check for required text
        if (isset($contentChecks['required_text'])) {
            foreach ($contentChecks['required_text'] as $text) {
                if (!str_contains($body, $text)) {
                    $errors[] = "Required text not found: '{$text}'";
                }
            }
        }

        // Check for forbidden text
        if (isset($contentChecks['forbidden_text'])) {
            foreach ($contentChecks['forbidden_text'] as $text) {
                if (str_contains($body, $text)) {
                    $errors[] = "Forbidden text found: '{$text}'";
                }
            }
        }

        // Check response size
        if (isset($contentChecks['response_size'])) {
            $size = strlen($body);
            $minSize = $contentChecks['response_size']['min_bytes'] ?? 0;
            $maxSize = $contentChecks['response_size']['max_bytes'] ?? PHP_INT_MAX;
            
            if ($size < $minSize) {
                $errors[] = "Response too small: {$size} bytes (minimum: {$minSize})";
            }
            if ($size > $maxSize) {
                $errors[] = "Response too large: {$size} bytes (maximum: {$maxSize})";
            }
        }

        // JSON validation
        if (isset($contentChecks['json_validation'])) {
            $jsonErrors = $this->validateJsonResponse($response, $contentChecks['json_validation']);
            $errors = array_merge($errors, $jsonErrors);
        }

        return ['errors' => $errors];
    }

    /**
     * Validate JSON API response
     */
    protected function validateJsonResponse($response, array $jsonConfig): array
    {
        $errors = [];
        
        try {
            $json = $response->json();
            
            // Check required fields
            if (isset($jsonConfig['required_fields'])) {
                foreach ($jsonConfig['required_fields'] as $field) {
                    if (!isset($json[$field])) {
                        $errors[] = "Required JSON field missing: '{$field}'";
                    }
                }
            }

            // Check expected values
            if (isset($jsonConfig['expected_values'])) {
                foreach ($jsonConfig['expected_values'] as $field => $expectedValue) {
                    if (!isset($json[$field]) || $json[$field] !== $expectedValue) {
                        $errors[] = "JSON field '{$field}' has unexpected value";
                    }
                }
            }

            // Check numeric ranges
            if (isset($jsonConfig['numeric_ranges'])) {
                foreach ($jsonConfig['numeric_ranges'] as $field => $range) {
                    if (isset($json[$field])) {
                        $value = $json[$field];
                        if (isset($range['min']) && $value < $range['min']) {
                            $errors[] = "JSON field '{$field}' below minimum: {$value} < {$range['min']}";
                        }
                        if (isset($range['max']) && $value > $range['max']) {
                            $errors[] = "JSON field '{$field}' above maximum: {$value} > {$range['max']}";
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $errors[] = 'Invalid JSON response: ' . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Check if service is in maintenance window
     */
    protected function isInMaintenanceWindow(Service $service): bool
    {
        if (!$service->maintenance_windows) {
            return false;
        }

        $now = now();
        $windows = $service->maintenance_windows;

        // Check weekly maintenance windows
        if (isset($windows['weekly'])) {
            foreach ($windows['weekly'] as $window) {
                $dayOfWeek = strtolower($now->format('l'));
                if ($dayOfWeek === $window['day']) {
                    $startTime = $now->copy()->setTimeFromTimeString($window['start']);
                    $endTime = $now->copy()->setTimeFromTimeString($window['end']);
                    
                    if ($now->between($startTime, $endTime)) {
                        return true;
                    }
                }
            }
        }

        // Check one-time maintenance windows
        if (isset($windows['one_time'])) {
            foreach ($windows['one_time'] as $window) {
                $start = \Carbon\Carbon::parse($window['start']);
                $end = \Carbon\Carbon::parse($window['end']);
                
                if ($now->between($start, $end)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Perform SSL certificate monitoring
     */
    protected function performSslCheck(Service $service): array
    {
        $errors = [];
        
        if (!$service->url || !str_starts_with($service->url, 'https://')) {
            return ['errors' => []];
        }

        try {
            $parsed = parse_url($service->url);
            $host = $parsed['host'];
            $port = $parsed['port'] ?? 443;

            $context = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);

            $client = @stream_socket_client(
                "ssl://{$host}:{$port}",
                $errno,
                $errstr,
                10,
                STREAM_CLIENT_CONNECT,
                $context
            );

            if ($client) {
                $cert = stream_context_get_params($client)['options']['ssl']['peer_certificate'];
                $certInfo = openssl_x509_parse($cert);
                
                // Check certificate expiry
                $expiryDate = \Carbon\Carbon::createFromTimestamp($certInfo['validTo_time_t']);
                $warningDays = $service->ssl_monitoring['expiry_warning_days'] ?? 30;
                
                if ($expiryDate->diffInDays(now()) <= $warningDays) {
                    $errors[] = "SSL certificate expires soon: {$expiryDate->format('Y-m-d')}";
                }

                fclose($client);
            } else {
                $errors[] = "SSL connection failed: {$errstr}";
            }

        } catch (\Exception $e) {
            $errors[] = 'SSL check failed: ' . $e->getMessage();
        }

        return ['errors' => $errors];
    }

    /**
     * Perform port connectivity check
     */
    protected function performPortCheck(Service $service): array
    {
        $errors = [];
        $ports = $service->port_monitoring['ports'] ?? [];
        $timeout = $service->port_monitoring['timeout'] ?? 5;

        foreach ($ports as $portConfig) {
            $port = $portConfig['number'];
            $protocol = $portConfig['protocol'] ?? 'tcp';
            
            $parsed = parse_url($service->url);
            $host = $parsed['host'] ?? $service->url;

            if ($protocol === 'tcp') {
                $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
                if (!$connection) {
                    $errors[] = "Port {$port}/tcp not accessible: {$errstr}";
                } else {
                    fclose($connection);
                }
            }
        }

        return ['errors' => $errors];
    }

    /**
     * Perform DNS resolution check
     */
    protected function performDnsCheck(Service $service): array
    {
        $errors = [];
        $dnsConfig = $service->dns_monitoring;

        try {
            $parsed = parse_url($service->url);
            $host = $parsed['host'] ?? $service->url;

            $records = dns_get_record($host, DNS_A);
            
            if (empty($records)) {
                $errors[] = "DNS resolution failed for {$host}";
            } elseif (isset($dnsConfig['expected_ips'])) {
                $resolvedIps = array_column($records, 'ip');
                $expectedIps = $dnsConfig['expected_ips'];
                
                if (empty(array_intersect($resolvedIps, $expectedIps))) {
                    $errors[] = "DNS resolved to unexpected IPs: " . implode(', ', $resolvedIps);
                }
            }

        } catch (\Exception $e) {
            $errors[] = 'DNS check failed: ' . $e->getMessage();
        }

        return ['errors' => $errors];
    }

    /**
     * Execute custom monitoring scripts
     */
    protected function executeCustomScripts(Service $service): array
    {
        $errors = [];
        $scripts = $service->custom_scripts;

        foreach ($scripts as $scriptName => $command) {
            try {
                $output = [];
                $returnCode = 0;
                exec($command . ' 2>&1', $output, $returnCode);
                
                if ($returnCode !== 0) {
                    $errors[] = "Custom script '{$scriptName}' failed with exit code {$returnCode}";
                }

            } catch (\Exception $e) {
                $errors[] = "Custom script '{$scriptName}' execution failed: " . $e->getMessage();
            }
        }

        return ['errors' => $errors];
    }

    /**
     * Check performance thresholds
     */
    protected function checkPerformanceThresholds($response, array $thresholds): array
    {
        $errors = [];
        
        // This would integrate with more detailed timing information
        // For now, we'll use response time as a placeholder
        
        return ['errors' => $errors];
    }

    /**
     * Get monitoring examples for a specific service type
     */
    public static function getExamplesForType(string $type): array
    {
        $examples = config('monitoring-examples.examples', []);
        
        return array_filter($examples, function($example) use ($type) {
            return str_contains(strtolower($example['name']), strtolower($type)) ||
                   str_contains(strtolower($example['description']), strtolower($type));
        });
    }

    /**
     * Get configuration template for service type
     */
    public static function getTemplate(string $type): array
    {
        return config("monitoring-examples.templates.{$type}", []);
    }
}
