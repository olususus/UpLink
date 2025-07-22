#!/bin/bash
# Database Setup and Troubleshooting Script for DBusWorld Status

echo "🔧 DBusWorld Status - Database Setup & Troubleshooting"
echo "======================================================="

# Check if we're in Laravel directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this from the Laravel root directory."
    exit 1
fi

echo "📍 Current directory: $(pwd)"
echo ""

# Test database connection
echo "🔌 Testing database connection..."
php artisan tinker --execute="
try {
    \DB::connection()->getPdo();
    echo 'Database connection: SUCCESS\n';
    echo 'Database name: ' . \DB::connection()->getDatabaseName() . '\n';
} catch (\Exception \$e) {
    echo 'Database connection: FAILED\n';
    echo 'Error: ' . \$e->getMessage() . '\n';
}
"

echo ""

# Check if tables exist
echo "📋 Checking if tables exist..."
php artisan tinker --execute="
try {
    if (Schema::hasTable('services')) {
        echo 'services table: EXISTS\n';
        echo 'Records: ' . \App\Models\Service::count() . '\n';
    } else {
        echo 'services table: MISSING\n';
    }
    
    if (Schema::hasTable('incidents')) {
        echo 'incidents table: EXISTS\n';
        echo 'Records: ' . \App\Models\Incident::count() . '\n';
    } else {
        echo 'incidents table: MISSING\n';
    }
    
    if (Schema::hasTable('status_checks')) {
        echo 'status_checks table: EXISTS\n';
        echo 'Records: ' . \App\Models\StatusCheck::count() . '\n';
    } else {
        echo 'status_checks table: MISSING\n';
    }
} catch (\Exception \$e) {
    echo 'Error checking tables: ' . \$e->getMessage() . '\n';
}
"

echo ""

# Show migration status
echo "📜 Migration status:"
php artisan migrate:status

echo ""

# Option to reset everything
read -p "🔄 Do you want to reset and recreate all tables? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🗑️  Dropping all tables..."
    php artisan migrate:reset --force
    
    echo "🏗️  Running fresh migrations..."
    php artisan migrate --force
    
    echo "🌱 Seeding database..."
    php artisan db:seed --force
    
    echo "✅ Database reset complete!"
else
    echo "⏭️  Skipping database reset."
fi

echo ""

# Clear all caches
echo "🧹 Clearing all caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo ""

# Test the application
echo "🧪 Testing application..."
php artisan tinker --execute="
try {
    \$services = \App\Models\Service::all();
    echo 'Application test: SUCCESS\n';
    echo 'Services loaded: ' . \$services->count() . '\n';
    foreach (\$services as \$service) {
        echo '- ' . \$service->name . ' (' . \$service->status . ')\n';
    }
} catch (\Exception \$e) {
    echo 'Application test: FAILED\n';
    echo 'Error: ' . \$e->getMessage() . '\n';
}
"

echo ""
echo "✅ Setup complete! Your status page should now work properly."
echo "🌐 Visit your website to check if everything is working."
