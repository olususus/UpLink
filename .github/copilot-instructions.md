<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

# DBusWorld Status Monitoring Website

This is a Laravel-based status monitoring website for DBusWorld services. The application includes:

## Key Features

-   Automatic monitoring of dbusworld.com website status
-   Manual status management for game server and Discord bot
-   Admin panel with authentication
-   Incident tracking and past incidents display
-   Responsive design similar to TruckersMP status page

## Technical Stack

-   **Framework**: Laravel 12
-   **Database**: MySQL
-   **Frontend**: Blade templates with Tailwind CSS
-   **Monitoring**: Guzzle HTTP client for website checks
-   **Scheduling**: Laravel task scheduler for automated monitoring

## Database Configuration

-   **Host**: localhost
-   **Database**: Status-DBus
-   **Username**: root
-   **Password**: 4KyMDdT9krw8BFR

## Admin Credentials

-   **Email**: varter.fanart@gmail.com
-   **Password**: Olek2009#

## Services Monitored

1. **DBusWorld Website** (dbusworld.com) - Automatic monitoring
2. **Game Server** - Manual status updates
3. **Discord Bot** - Manual status updates

## Special Rules

-   503 HTTP status from dbusworld.com indicates maintenance mode
-   Incident descriptions are always added manually
-   Status updates are automatic for website, manual for game server and Discord bot
