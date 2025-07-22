<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Advanced Monitoring Examples
    |--------------------------------------------------------------------------
    |
    | This file contains comprehensive examples of different monitoring
    | configurations for various types of services and scenarios.
    |
    */

    'examples' => [
        
        'ecommerce_website' => [
            'name' => 'E-commerce Website',
            'url' => 'https://shop.example.com',
            'description' => 'Main e-commerce platform with cart and checkout functionality',
            'content_checks' => [
                'required_text' => ['Add to Cart', 'Checkout', 'Shopping Cart'],
                'forbidden_text' => ['Error 500', 'Site Maintenance', 'Database Error'],
                'required_elements' => [
                    ['selector' => '.product-grid', 'count' => 1],
                    ['selector' => '.search-form', 'count' => 1],
                    ['selector' => '.user-account', 'count' => 1]
                ],
                'json_validation' => [
                    'path' => '/api/cart/status',
                    'required_fields' => ['available', 'items_count', 'total']
                ],
                'response_size' => [
                    'min_bytes' => 5000,
                    'max_bytes' => 100000
                ]
            ],
            'ssl_monitoring' => [
                'enabled' => true,
                'check_expiry' => true,
                'expiry_warning_days' => 30,
                'verify_certificate' => true,
                'check_revocation' => true
            ],
            'performance_thresholds' => [
                'dns_lookup' => 50,
                'tcp_connect' => 100,
                'ssl_handshake' => 200,
                'first_byte' => 800,
                'total_time' => 2000
            ],
            'alert_escalation' => [
                'levels' => [
                    [
                        'delay_minutes' => 0,
                        'channels' => ['discord', 'email'],
                        'recipients' => ['devops@company.com']
                    ],
                    [
                        'delay_minutes' => 10,
                        'channels' => ['sms'],
                        'recipients' => ['manager@company.com']
                    ]
                ]
            ]
        ],

        'api_service' => [
            'name' => 'REST API Service',
            'url' => 'https://api.example.com',
            'description' => 'Core REST API for mobile and web applications',
            'http_headers' => [
                'Authorization' => 'Bearer your-monitoring-token',
                'Accept' => 'application/json',
                'User-Agent' => 'StatusMonitor/1.0'
            ],
            'content_checks' => [
                'json_validation' => [
                    'path' => '/health',
                    'required_fields' => ['status', 'version', 'database', 'cache'],
                    'expected_values' => [
                        'status' => 'ok',
                        'database' => 'connected'
                    ]
                ]
            ],
            'performance_thresholds' => [
                'total_time' => 500
            ],
            'auth_config' => [
                'type' => 'bearer',
                'token' => 'your-api-monitoring-token'
            ],
            'webhook_config' => [
                'status_change' => 'https://monitoring.example.com/webhook',
                'method' => 'POST',
                'headers' => ['Authorization' => 'Bearer webhook-token'],
                'timeout' => 5
            ]
        ],

        'database_service' => [
            'name' => 'Database Server',
            'url' => 'tcp://db.example.com:5432',
            'description' => 'PostgreSQL primary database server',
            'type' => 'tcp',
            'port_monitoring' => [
                'enabled' => true,
                'ports' => [
                    ['number' => 5432, 'protocol' => 'tcp']
                ],
                'timeout' => 5
            ],
            'custom_scripts' => [
                'health_check' => 'pg_isready -h db.example.com -p 5432',
                'performance_check' => 'psql -h db.example.com -c "SELECT COUNT(*) FROM users;"'
            ],
            'consecutive_failures_threshold' => 2,
            'retry_attempts' => 5,
            'retry_delay' => 10
        ],

        'cdn_endpoint' => [
            'name' => 'CDN Static Assets',
            'url' => 'https://cdn.example.com',
            'description' => 'Content delivery network for static assets',
            'monitoring_regions' => [
                'enabled' => true,
                'regions' => ['us-east', 'us-west', 'eu-central', 'asia-pacific'],
                'require_all_pass' => false,
                'failure_threshold_percentage' => 25
            ],
            'content_checks' => [
                'response_size' => [
                    'min_bytes' => 100,
                    'max_bytes' => 10000000
                ]
            ],
            'performance_thresholds' => [
                'total_time' => 1000
            ],
            'expected_status_codes' => '200,301,302'
        ],

        'game_server' => [
            'name' => 'Game Server',
            'url' => 'https://game.example.com/api/status',
            'description' => 'Main game server with player statistics',
            'content_checks' => [
                'json_validation' => [
                    'path' => '/api/server/status',
                    'required_fields' => ['online_players', 'max_players', 'server_load'],
                    'numeric_ranges' => [
                        'online_players' => ['min' => 0, 'max' => 1000],
                        'server_load' => ['min' => 0, 'max' => 100]
                    ]
                ]
            ],
            'port_monitoring' => [
                'enabled' => true,
                'ports' => [
                    ['number' => 80, 'protocol' => 'tcp'],
                    ['number' => 443, 'protocol' => 'tcp'],
                    ['number' => 7777, 'protocol' => 'udp'],
                    ['number' => 7778, 'protocol' => 'tcp']
                ]
            ],
            'maintenance_windows' => [
                'weekly' => [
                    [
                        'day' => 'tuesday',
                        'start' => '03:00',
                        'end' => '05:00',
                        'timezone' => 'UTC'
                    ]
                ]
            ]
        ],

        'microservice' => [
            'name' => 'User Authentication Service',
            'url' => 'https://auth.example.com',
            'description' => 'Microservice handling user authentication and sessions',
            'auth_config' => [
                'type' => 'api_key',
                'key' => 'X-API-Key',
                'value' => 'your-service-api-key'
            ],
            'content_checks' => [
                'json_validation' => [
                    'path' => '/health',
                    'required_fields' => ['service', 'version', 'dependencies'],
                    'dependency_checks' => [
                        'database' => 'healthy',
                        'redis' => 'healthy',
                        'jwt_service' => 'healthy'
                    ]
                ]
            ],
            'performance_thresholds' => [
                'total_time' => 300
            ],
            'custom_scripts' => [
                'before_check' => 'curl -X POST https://auth.example.com/monitoring/pre-check',
                'after_check' => 'curl -X POST https://auth.example.com/monitoring/post-check'
            ]
        ],

        'wordpress_site' => [
            'name' => 'WordPress Blog',
            'url' => 'https://blog.example.com',
            'description' => 'Company blog powered by WordPress',
            'content_checks' => [
                'required_text' => ['WordPress', 'Blog', 'Recent Posts'],
                'forbidden_text' => ['Fatal error', 'Database connection error'],
                'required_elements' => [
                    ['selector' => '.site-header', 'count' => 1],
                    ['selector' => '.post-list', 'count' => 1],
                    ['selector' => '.site-footer', 'count' => 1]
                ]
            ],
            'ssl_monitoring' => [
                'enabled' => true,
                'check_expiry' => true,
                'expiry_warning_days' => 15
            ],
            'maintenance_windows' => [
                'weekly' => [
                    [
                        'day' => 'sunday',
                        'start' => '02:00',
                        'end' => '04:00',
                        'timezone' => 'UTC'
                    ]
                ]
            ]
        ],

        'external_api' => [
            'name' => 'Third-party Payment API',
            'url' => 'https://api.payment-provider.com',
            'description' => 'External payment processing API dependency',
            'auth_config' => [
                'type' => 'basic',
                'username' => 'api_user',
                'password' => 'secure_password'
            ],
            'content_checks' => [
                'json_validation' => [
                    'path' => '/v1/status',
                    'required_fields' => ['status', 'processing_available']
                ]
            ],
            'alert_escalation' => [
                'levels' => [
                    [
                        'delay_minutes' => 0,
                        'channels' => ['discord'],
                        'recipients' => ['payments@company.com']
                    ],
                    [
                        'delay_minutes' => 5,
                        'channels' => ['email', 'sms'],
                        'recipients' => ['cto@company.com']
                    ]
                ]
            ],
            'retry_attempts' => 5,
            'retry_delay' => 3
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration Templates
    |--------------------------------------------------------------------------
    |
    | Pre-built templates for common service types
    |
    */

    'templates' => [
        'website' => [
            'timeout' => 10,
            'expected_status_codes' => '200-299',
            'ssl_monitoring' => ['enabled' => true, 'check_expiry' => true],
            'performance_thresholds' => ['total_time' => 3000],
            'retry_attempts' => 3
        ],
        
        'api' => [
            'timeout' => 5,
            'expected_status_codes' => '200-299',
            'http_headers' => ['Accept' => 'application/json'],
            'performance_thresholds' => ['total_time' => 1000],
            'retry_attempts' => 3
        ],
        
        'tcp_service' => [
            'timeout' => 5,
            'port_monitoring' => ['enabled' => true],
            'retry_attempts' => 5,
            'consecutive_failures_threshold' => 2
        ]
    ]
];
