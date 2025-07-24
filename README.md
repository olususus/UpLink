git clone https://github.com/your-username/status-monitor.git

# Status Monitoring - Quick Setup

A professional Laravel-based status monitoring application.

## Requirements

-   PHP 8.2 or higher
-   MySQL 5.7+ or PostgreSQL 10+
-   Composer
-   Node.js 16+ and npm
-   Web server (Apache/Nginx)

## Installation

```bash
git clone https://github.com/DBus-World/Status.git
cd Status
composer install
npm install
cp .env.example .env
php artisan key:generate
# Edit .env for your DB and mail settings
php artisan migrate --seed
npm run build
```

## Scheduling

To enable automatic monitoring, set up a cron job:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

Or run manually:

```bash
php artisan status:monitor
```

## Support & Community

-   [Join our Discord](https://discord.gg/ZXjeKkNQDF)
-   Issues: Use GitHub Issues

---

_Made by sprawdzany_

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
-   **Community & Help**: [Join our Discord](https://discord.gg/ZXjeKkNQDF)

---

_Made by sprawdzany_
