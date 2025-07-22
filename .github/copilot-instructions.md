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

Use environment variables for database configuration:

-   **Host**: Set via `DB_HOST`
-   **Database**: Set via `DB_DATABASE`
-   **Username**: Set via `DB_USERNAME`
-   **Password**: Set via `DB_PASSWORD`

## Admin Credentials

Set via environment variables:

-   **Email**: Set via `ADMIN_EMAIL`
-   **Password**: Set via `ADMIN_PASSWORD`

## Services Monitored

1. **Custom Services** - Configurable monitoring with custom error detection
2. **Game Server** - Manual status updates
3. **Discord Bot** - Manual status updates

## Special Rules

-   Custom error monitoring patterns can be configured per service
-   Incident descriptions are always added manually
-   Status updates are automatic for monitored services, manual for others
