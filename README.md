# Status Monitor

A customizable Laravel-based status monitoring application perfect for monitoring your services and websites.

## ✨ Features

-   **Real-time Service Monitoring** - HTTP/HTTPS endpoint monitoring with custom intervals
-   **Advanced Error Detection** - Configure custom error patterns and expected status codes
-   **Flexible Configuration** - Custom HTTP headers, timeouts, and redirect handling
-   **Admin Dashboard** - Easy-to-use interface for managing services and incidents
-   **Incident Tracking** - Track and resolve service incidents with detailed history
-   **Responsive Design** - Mobile-friendly interface
-   **Laravel Forge Ready** - Optimized for easy deployment

## 🚀 Quick Deploy with Laravel Forge

1. **Create new site in Forge**

    - Repository: `https://github.com/your-username/status-monitor`
    - Branch: `main`

2. **Set Environment Variables** in Forge:

    ```bash
    APP_NAME="Your Status Page"
    APP_URL=https://your-domain.com

    # Admin Access
    ADMIN_EMAIL=admin@yourcompany.com
    ADMIN_PASSWORD=your-secure-password

    # Database (configure in Forge)
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=forge
    DB_USERNAME=forge
    DB_PASSWORD=your-db-password
    ```

3. **Deploy Script** (Forge will handle this):

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

4. **Set up Scheduler** in Forge:
    ```bash
    * * * * * $FORGE_PHP $FORGE_SITE_PATH/artisan schedule:run >> /dev/null 2>&1
    ```

## 📋 Manual Installation

<details>
<summary>Click to expand manual installation steps</summary>

### Requirements

-   PHP 8.1+
-   MySQL 5.7+
-   Composer
-   Node.js & npm

### Steps

1. Clone repository: `git clone https://github.com/your-username/status-monitor.git`
2. Install dependencies: `composer install && npm install`
3. Copy environment: `cp .env.example .env`
4. Generate key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed admin user: `php artisan db:seed`
8. Build assets: `npm run production`
9. Set up cron job for monitoring

</details>

## 🎨 Customization

### Branding

-   **App Name**: Change `APP_NAME` in `.env`
-   **Colors**: Edit `tailwind.config.js`
-   **Logo**: Replace in `resources/views/layouts/app.blade.php`
-   **Favicon**: Replace `public/favicon.ico`

### Services Configuration

Access the admin panel at `/admin` to:

-   Add/edit monitored services
-   Configure error patterns (regex or plain text)
-   Set custom HTTP headers for API monitoring
-   Define expected status codes
-   Set monitoring intervals

### Advanced Monitoring Examples

**API with Authentication:**

```
URL: https://api.yoursite.com/health
Headers: Authorization: Bearer your-token
Expected Codes: 200
Error Patterns: "error", "failed", "/5\d{2}/"
```

**Website with Custom User-Agent:**

```
URL: https://yoursite.com
Headers: User-Agent: StatusMonitor/1.0
Expected Codes: 200-299,301,302
Error Patterns: "maintenance", "down", "/error.*occurred/i"
```

## 🔧 Configuration Options

| Setting                 | Description                  | Example                 |
| ----------------------- | ---------------------------- | ----------------------- |
| `check_interval`        | How often to check (seconds) | `300` (5 minutes)       |
| `timeout`               | Request timeout (seconds)    | `10`                    |
| `expected_status_codes` | Healthy status codes         | `200-299,301,302`       |
| `error_patterns`        | Error detection patterns     | `["error", "/5\d{2}/"]` |
| `follow_redirects`      | Follow HTTP redirects        | `true`                  |

## 📊 Status Page

Your status page will be available at your domain root. Customize the appearance by editing:

-   `resources/views/status/index.blade.php` - Main status page
-   `resources/views/layouts/app.blade.php` - Layout and navigation
-   `resources/css/app.css` - Custom styles

## 🔒 Security

-   Admin access is protected by authentication
-   Environment variables for all sensitive data
-   CSRF protection on all forms
-   Input validation on all endpoints

## 📈 Performance

-   Optimized for Laravel Forge deployment
-   Queue system for background monitoring
-   Cached configuration and routes
-   Minimal database queries on status page

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

Open source under the MIT License.

## 🆘 Support

-   📖 [Documentation](https://github.com/your-username/status-monitor/wiki)
-   🐛 [Issues](https://github.com/your-username/status-monitor/issues)
-   💬 [Discussions](https://github.com/your-username/status-monitor/discussions)
    -   -   -   -   -   cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
    ```

    ```

6. **Set permissions:**
    ```bash
    chmod -R 755 storage bootstrap/cache
    ```

## Technical Details

### Monitoring Logic

-   **Automatic Services**: Monitored via Guzzle HTTP client
-   **Manual Services**: Status updated through admin panel
-   **Status Types**: operational, degraded, maintenance, outage
-   **Special Cases**: 503 HTTP status = maintenance mode

### Scheduled Tasks

-   **Status Monitoring**: Runs every 5 minutes (`status:monitor`)
-   **Command**: `php artisan status:monitor`

---

Built with Laravel 12 • Ready for Laravel Forge deployment
