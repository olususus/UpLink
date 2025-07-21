# DBusWorld Status Monitoring Website

A Laravel-based status monitoring website for DBusWorld services, featuring automatic monitoring, manual status management, and incident tracking.

## Features

-   🔄 **Automatic Monitoring**: Monitors dbusworld.com every 5 minutes
-   🎮 **Manual Status Management**: Admin controls for Game Server and Discord Bot
-   📊 **Admin Dashboard**: Comprehensive admin panel with authentication
-   📝 **Incident Tracking**: Complete incident management system
-   📱 **Responsive Design**: Similar to TruckersMP status page design
-   ⚡ **Real-time Updates**: Auto-refresh functionality
-   🔧 **Maintenance Detection**: Special handling for 503 status codes

## Services Monitored

1. **DBusWorld Website** (`https://dbusworld.com`)

    - Automatic monitoring every 5 minutes
    - 503 status code triggers maintenance mode
    - Response time tracking

2. **Game Server**

    - Manual status updates via admin panel
    - Custom status messages

3. **Discord Bot**
    - Manual status updates via admin panel
    - Custom status messages

## Admin Access

-   **URL**: `/login`
-   **Email**: `varter.fanart@gmail.com`
-   **Password**: `Olek2009#`

## Database Configuration

-   **Host**: localhost
-   **Database**: Status-DBus
-   **Username**: root
-   **Password**: 4KyMDdT9krw8BFR

## Installation & Deployment

### Server Requirements

-   PHP 8.2+
-   MySQL Database
-   Composer
-   Node.js & NPM
-   Web server (Apache/Nginx)

### Deployment Steps

1. **Upload project files to server**

2. **Install dependencies:**

    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run build
    ```

3. **Configure environment:**

    - Ensure `.env` file has correct database credentials
    - Generate app key: `php artisan key:generate`

4. **Set up database:**

    ```bash
    php artisan migrate --force
    php artisan db:seed --force
    ```

5. **Configure task scheduler:**
   Add to server crontab:

    ```
    * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
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
