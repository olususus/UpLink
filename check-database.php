<?php

// Simple database checker for DBusWorld Status
// Run with: php check-database.php

echo "🔍 DBusWorld Status Database Checker\n";
echo "====================================\n\n";

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Test basic connection
    echo "📡 Testing database connection...\n";
    $pdo = DB::connection()->getPdo();
    echo "✅ Connection successful!\n";
    echo "📊 Database: " . DB::connection()->getDatabaseName() . "\n\n";
    
    // Show all tables
    echo "📋 Tables in database:\n";
    $tables = DB::select("SHOW TABLES");
    
    if (empty($tables)) {
        echo "⚠️  No tables found!\n";
    } else {
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "  - $tableName\n";
        }
    }
    echo "\n";
    
    // Check specific tables
    $requiredTables = ['services', 'incidents', 'status_checks', 'users'];
    
    echo "🔍 Checking required tables:\n";
    foreach ($requiredTables as $table) {
        if (Schema::hasTable($table)) {
            $count = DB::table($table)->count();
            echo "✅ $table (records: $count)\n";
        } else {
            echo "❌ $table - MISSING\n";
        }
    }
    echo "\n";
    
    // Show migration status
    echo "📜 Migration status:\n";
    if (Schema::hasTable('migrations')) {
        $migrations = DB::table('migrations')->orderBy('batch')->get();
        if ($migrations->isEmpty()) {
            echo "⚠️  No migrations recorded\n";
        } else {
            foreach ($migrations as $migration) {
                echo "  ✅ {$migration->migration} (batch: {$migration->batch})\n";
            }
        }
    } else {
        echo "❌ migrations table doesn't exist\n";
    }
    echo "\n";
    
    // Test models if tables exist
    if (Schema::hasTable('services')) {
        echo "🧪 Testing Service model:\n";
        $services = \App\Models\Service::all();
        echo "  📊 Found {$services->count()} services\n";
        foreach ($services as $service) {
            echo "    - {$service->name} ({$service->status})\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📋 Check your .env file database settings:\n";
    echo "  DB_DATABASE=" . env('DB_DATABASE') . "\n";
    echo "  DB_USERNAME=" . env('DB_USERNAME') . "\n";
    echo "  DB_HOST=" . env('DB_HOST') . "\n";
}

echo "\n✅ Database check complete!\n";
