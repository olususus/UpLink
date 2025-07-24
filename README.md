<img width="1140" height="71" alt="uplink-logo" src="https://github.com/user-attachments/assets/09962b5d-b89c-42f9-b3f5-b0797edd00e8" />

---

<img width="1877" height="958" alt="image" src="https://github.com/user-attachments/assets/9e20ee2f-7acf-4567-b38f-0f05c86cbca0" />

A professional Laravel-based status monitoring application for tracking service uptime and managing incidents. Built for organizations that need reliable monitoring with customizable error detection and automated alerts.

## Features

---

## Features

-   HTTP/HTTPS monitoring for websites, APIs, and web services (configurable intervals)
-   Advanced error detection (custom regex patterns, status code validation)
-   Incident management: track, document, and resolve service incidents
-   Automated email and Discord notifications for downtime and recovery
-   Uptime tracking: historical uptime percentages and response time metrics
-   Admin dashboard for managing services and incidents
-   Customizable interface: theme colors, branding, auto-refresh
-   Data retention: configurable cleanup of old incidents and monitoring data

## Requirements

-   PHP 8.1 or higher
-   MySQL 5.7+ or PostgreSQL 10+
-   Composer
-   Node.js 16+ and npm
-   Web server (Apache/Nginx)

## Installation

### Quick Setup

```bash
# Clone the repository
git clone https://github.com/your-username/status-monitor.git
cd status-monitor

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# Then run migrations
php artisan migrate

# Create admin user and seed sample services
php artisan db:seed

# Build frontend assets
npm run build

# Set up cron job for monitoring (see Scheduling section)
```

### Environment Configuration

Configure these essential variables in your `.env` file:

```bash
# Application
APP_NAME="Your Status Page"
APP_URL=https://status.yourcompany.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=status_monitor
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Admin Account
ADMIN_EMAIL=admin@yourcompany.com
ADMIN_PASSWORD=secure_password_here

# Email (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Scheduling

Add this cron job to run monitoring checks every 5 minutes:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

Or manually run monitoring:

```bash
php artisan status:monitor
```

## Service Configuration

### Basic Service Setup

Access the admin panel at `/admin` to configure monitored services:

| Field          | Description            | Example                |
| -------------- | ---------------------- | ---------------------- |
| Name           | Service identifier     | "Main Website"         |
| URL            | Endpoint to monitor    | `https://yoursite.com` |
| Check Interval | Seconds between checks | `300` (5 minutes)      |
| Timeout        | Request timeout        | `10` seconds           |

### Advanced Monitoring Options

**Expected Status Codes**

```
200-299,301,302    # Accept success and redirect codes
200,201,204        # Specific codes only
```

**Error Pattern Detection**

```json
[
    "error",
    "maintenance",
    "temporarily unavailable",
    "/5\\d{2}/",
    "/error.*occurred/i"
]
```

**Custom HTTP Headers**

```json
{
    "Authorization": "Bearer your-api-token",
    "User-Agent": "StatusMonitor/1.0",
    "Accept": "application/json"
}
```

### Service Types

-   **Automatic**: Monitored via HTTP requests with configurable intervals
-   **Manual**: Status updated manually through admin interface

## Customization

### Theme and Branding

```bash
# Theme colors
APP_THEME_PRIMARY_COLOR=#007bff
APP_THEME_SUCCESS_COLOR=#28a745
APP_THEME_WARNING_COLOR=#ffc107
APP_THEME_DANGER_COLOR=#dc3545

# Company branding
COMPANY_NAME="Your Company Name"
COMPANY_SUPPORT_URL=https://support.yourcompany.com
SUPPORT_EMAIL=support@yourcompany.com
```

### Auto-Refresh Status Page

```bash
AUTO_REFRESH_ENABLED=true
AUTO_REFRESH_INTERVAL=30  # seconds
```

### Uptime Display

```bash
SHOW_UPTIME_PERCENTAGE=true
```

### Email Notifications

```bash
NOTIFICATIONS_EMAIL_ENABLED=true
NOTIFICATION_EMAIL=alerts@yourcompany.com
```

### Discord Notifications

```bash
NOTIFICATIONS_DISCORD_ENABLED=true
DISCORD_WEBHOOK_URL=https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN
```

### Data Retention

```bash
INCIDENT_RETENTION_DAYS=90  # Keep incidents for 90 days
```

## API Endpoints

### Public Status API

```bash
GET /api/status          # Overall system status
GET /api/services        # All services status
GET /api/services/{id}   # Individual service status
```

### Response Format

```json
{
    "status": "operational",
    "services": [
        {
            "id": 1,
            "name": "Main Website",
            "status": "operational",
            "uptime_percentage": 99.95,
            "last_checked": "2025-07-22T10:30:00Z"
        }
    ]
}
```

## Deployment

### Laravel Forge

1. Create new site in Forge
2. Set environment variables in Forge dashboard
3. Configure deployment script:

```bash
cd $FORGE_SITE_PATH
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci --only=production
npm run build
$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan queue:restart
```

4. Set up scheduler in Forge:

```bash
* * * * * $FORGE_PHP $FORGE_SITE_PATH/artisan schedule:run >> /dev/null 2>&1
```

### Manual Deployment

```bash
# Production optimizations
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Management Commands

```bash
# Monitor all services manually
php artisan status:monitor

# Clean up old incidents
php artisan incidents:cleanup

# Clean up incidents older than specific days
php artisan incidents:cleanup --days=30

# Create admin user
php artisan db:seed --class=UserSeeder
```

## File Structure

```
app/
├── Console/Commands/
│   ├── MonitorServices.php      # Main monitoring logic
│   └── CleanupOldIncidents.php  # Data cleanup
├── Http/Controllers/
│   ├── AdminController.php      # Admin dashboard
│   ├── ServiceController.php    # Service management
│   └── StatusController.php     # Public status page
├── Mail/
│   └── ServiceStatusChanged.php # Email notifications
└── Models/
    ├── Service.php              # Service model
    ├── Incident.php             # Incident tracking
    └── StatusCheck.php          # Monitoring history

resources/views/
├── admin/                       # Admin interface
├── status/                      # Public status page
└── emails/                      # Email templates
```

## Troubleshooting

### Common Issues

**Monitoring not running**

-   Check cron job is set up correctly
-   Verify Laravel scheduler is working: `php artisan schedule:list`
-   Run manually: `php artisan status:monitor`

**Services showing as down incorrectly**

-   Check timeout settings (increase if needed)
-   Verify expected status codes
-   Review error patterns for false positives
-   Check HTTP headers requirements

**Email notifications not sending**

-   Verify MAIL\_ environment variables
-   Test with: `php artisan tinker` then `Mail::raw('test', function($m) { $m->to('test@example.com')->subject('test'); });`
-   Check mail logs

**Performance issues**

-   Enable caching: `php artisan config:cache`
-   Optimize autoloader: `composer dump-autoload --optimize`
-   Consider increasing check intervals for less critical services

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Make your changes and test thoroughly
4. Submit a pull request with detailed description

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Support

-   **Documentation**: See `/docs` directory
-   **Issues**: Report bugs on GitHub Issues
-   **Support**: [Join The Discord](https://discord.gg/ZXjeKkNQDF)

---

_Made by Sprawdzany_
