# DBusWorld Status Monitoring Website

## Deployment Instructions

### 1. Server Requirements

-   PHP 8.2+
-   MySQL Database
-   Composer
-   Node.js & NPM
-   Web server (Apache/Nginx)

### 2. Database Setup

Create the MySQL database with the following credentials:

-   **Host**: localhost
-   **Database**: Status-DBus
-   **Username**: root
-   **Password**: 4KyMDdT9krw8BFR

### 3. Deployment Steps

1. **Clone/Upload the project files to the server**

2. **Install PHP dependencies:**

    ```bash
    composer install --optimize-autoloader --no-dev
    ```

3. **Install and build frontend assets:**

    ```bash
    npm install
    npm run build
    ```

4. **Configure environment:**

    - Ensure `.env` file has correct database credentials
    - Generate application key: `php artisan key:generate`

5. **Run database migrations and seed:**

    ```bash
    php artisan migrate --force
    php artisan db:seed --force
    ```

6. **Set up task scheduler:**
   Add to crontab:

    ```
    * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
    ```

7. **Set proper permissions:**
    ```bash
    chmod -R 755 storage bootstrap/cache
    ```

### 4. Admin Access

-   **URL**: `yourdomain.com/login`
-   **Email**: varter.fanart@gmail.com
-   **Password**: Olek2009#

### 5. Features

-   **Automatic monitoring** of dbusworld.com (every 5 minutes)
-   **Manual status management** for Game Server and Discord Bot
-   **Admin panel** for managing services and incidents
-   **Past incidents tracking**
-   **Auto-refresh** on status page

### 6. Services Monitored

1. **DBusWorld Website** (https://dbusworld.com) - Automatic
2. **Game Server** - Manual updates via admin panel
3. **Discord Bot** - Manual updates via admin panel

### 7. Special Features

-   503 HTTP status from dbusworld.com triggers maintenance mode
-   Real-time status updates
-   Incident management system
-   Responsive design similar to TruckersMP status page

## Local Development Note

This project requires a MySQL server with the specified credentials to run locally. On the server with Laravel Forge, it will work seamlessly.
