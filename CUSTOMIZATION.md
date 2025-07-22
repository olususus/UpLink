# Customization Guide

This guide will help you customize your Status Monitor to match your brand and requirements.

## 🎨 Branding & Appearance

### App Name & Company Info

Edit your `.env` file:

```env
APP_NAME="Your Status Page"
COMPANY_NAME="Your Company Name"
SUPPORT_EMAIL=support@yourcompany.com
SUPPORT_URL=https://yourcompany.com/support
TWITTER_HANDLE=yourcompany
```

### Theme Colors

Customize colors in `.env`:

```env
THEME_PRIMARY_COLOR=#3b82f6    # Main brand color
THEME_SUCCESS_COLOR=#10b981    # Operational status
THEME_WARNING_COLOR=#f59e0b    # Degraded status
THEME_DANGER_COLOR=#ef4444     # Outage status
THEME_INFO_COLOR=#06b6d4       # Maintenance status
```

### Logo & Favicon

-   Replace `public/favicon.ico` with your favicon
-   Edit `resources/views/layouts/app.blade.php` to add your logo
-   Update meta tags for social sharing

### Custom CSS

Add custom styles in `resources/css/app.css`:

```css
/* Custom styles */
.custom-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.status-card {
    border-left: 4px solid var(--primary-color);
}
```

## 🔧 Monitoring Configuration

### Default Settings

Configure monitoring defaults in `.env`:

```env
DEFAULT_CHECK_INTERVAL=300      # 5 minutes
DEFAULT_TIMEOUT=10             # 10 seconds
MAX_CHECK_INTERVAL=3600        # 1 hour maximum
MIN_CHECK_INTERVAL=60          # 1 minute minimum
```

### Status Page Options

```env
SHOW_UPTIME_PERCENTAGE=true    # Show uptime percentages
SHOW_RESPONSE_TIMES=true       # Show response time graphs
UPTIME_CALCULATION_DAYS=30     # Calculate uptime over 30 days
INCIDENT_RETENTION_DAYS=90     # Keep incident history for 90 days
AUTO_REFRESH_INTERVAL=30       # Auto-refresh every 30 seconds
```

## 📊 Service Configuration Examples

### Basic Website Monitoring

```
Name: Main Website
URL: https://yoursite.com
Type: Automatic
Check Interval: 300 seconds
Expected Status Codes: 200-299,301,302
```

### API Endpoint with Authentication

```
Name: User API
URL: https://api.yoursite.com/v1/health
Type: Automatic
HTTP Headers:
  Authorization: Bearer your-api-token
  User-Agent: StatusMonitor/1.0
Expected Status Codes: 200
Error Patterns: ["error", "failed"]
```

### Database Health Check

```
Name: Database
URL: https://yoursite.com/health/database
Type: Automatic
Expected Status Codes: 200
Error Patterns: ["connection failed", "timeout", "/database.*error/i"]
```

### External Service Dependency

```
Name: Payment Gateway
URL: https://api.stripe.com/v1/charges
Type: Automatic
HTTP Headers:
  Authorization: Bearer sk_test_...
Expected Status Codes: 401
Timeout: 15
```

## 🎯 Advanced Error Detection

### Plain Text Patterns

Monitor for specific error messages:

-   `"maintenance"`
-   `"service unavailable"`
-   `"temporarily down"`

### Regex Patterns

Use regex for complex pattern matching:

-   `"/5\d{2} server error/i"` - Any 5xx server error
-   `"/error.*occurred/i"` - Any error occurrence
-   `"/database.*connection/i"` - Database connection issues

### Multiple Patterns

You can add multiple patterns per service. If ANY pattern matches, the service is marked as degraded.

## 🔗 Custom URLs & Routes

### Status Page URL

By default, the status page is at `/`. To change this, edit `routes/web.php`:

```php
Route::get('/status', [StatusController::class, 'index'])->name('status');
```

### Admin Panel URL

Admin panel is at `/admin`. To change:

```php
Route::prefix('admin-panel')->group(function () {
    // Admin routes
});
```

## 📱 Mobile Customization

The interface is responsive by default. To customize mobile appearance:

1. Edit `resources/views/layouts/app.blade.php`
2. Modify Tailwind classes for mobile-first design
3. Add custom mobile CSS in `resources/css/app.css`

## 🔔 Notification Customization (Coming Soon)

Prepare for notifications by setting:

```env
NOTIFICATIONS_EMAIL_ENABLED=false
NOTIFICATIONS_SLACK_ENABLED=false
NOTIFICATIONS_DISCORD_ENABLED=false
```

## 🚀 Performance Optimization

### Caching

For better performance in production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Optimization

-   Index frequently queried fields
-   Archive old status checks regularly
-   Use database connection pooling

### CDN & Assets

-   Use a CDN for static assets
-   Enable Gzip compression
-   Optimize images and icons

## 🔒 Security Customization

### Admin User Management

Create additional admin users via tinker:

```php
php artisan tinker
User::create([
    'name' => 'Admin Name',
    'email' => 'admin@yourcompany.com',
    'password' => Hash::make('secure-password'),
    'email_verified_at' => now(),
]);
```

### Rate Limiting

Adjust rate limits in `app/Http/Kernel.php`:

```php
'api' => [
    'throttle:60,1',
],
```

## 📝 Custom Views

### Override Status Page Layout

Copy and modify:

-   `resources/views/status/index.blade.php` - Main status page
-   `resources/views/components/service-status.blade.php` - Service cards
-   `resources/views/components/incident-timeline.blade.php` - Incident display

### Add Custom Pages

Create new pages by adding routes and views:

```php
Route::get('/maintenance-schedule', function () {
    return view('custom.maintenance-schedule');
});
```

## 🎛️ Configuration Management

All configuration is managed through:

1. Environment variables (`.env`)
2. Config files (`config/status.php`)
3. Database settings (admin panel)

Changes to `.env` require an application restart in production.

## 📚 Further Customization

For advanced customization:

1. Modify the Service model for additional fields
2. Create custom monitoring commands
3. Add webhooks for external integrations
4. Implement custom notification channels

Need help? Check the discussions section or open an issue!
