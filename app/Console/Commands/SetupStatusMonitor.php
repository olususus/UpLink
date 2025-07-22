<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SetupStatusMonitor extends Command
{
    protected $signature = 'status:setup {--force : Force setup even if already configured}';
    protected $description = 'Setup Status Monitor with initial configuration';

    public function handle()
    {
        $this->info('🚀 Setting up Status Monitor...');

        if (User::count() > 0 && !$this->option('force')) {
            $this->warn('⚠️  Status Monitor appears to already be set up.');
            $this->line('Use --force to run setup anyway.');
            return 1;
        }

        // Check if database is configured
        try {
            \DB::connection()->getPdo();
            $this->info('✅ Database connection successful');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed');
            $this->line('Please configure your database in the .env file');
            return 1;
        }

        // Run migrations
        $this->info('🗄️  Running database migrations...');
        $this->call('migrate', ['--force' => true]);

        // Create admin user
        $this->info('👤 Setting up admin user...');
        $this->createAdminUser();

        // Ask about example services
        if ($this->confirm('📊 Would you like to add example services for testing?', true)) {
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\ExampleServiceSeeder',
                '--force' => true
            ]);
            $this->info('✅ Example services created');
        }

        // Setup scheduler
        $this->info('⏰ Scheduler setup information:');
        $this->line('Add this line to your crontab for monitoring:');
        $this->line('* * * * * cd ' . base_path() . ' && php artisan schedule:run >> /dev/null 2>&1');

        // Cache configuration
        $this->info('⚡ Caching configuration...');
        $this->call('config:cache');

        $this->info('');
        $this->info('🎉 Status Monitor setup complete!');
        $this->line('💻 Admin panel: ' . url('/admin'));
        $this->line('📊 Status page: ' . url('/'));
        $this->line('📚 Customization guide: Check CUSTOMIZATION.md');

        return 0;
    }

    private function createAdminUser()
    {
        // Try to get from environment first
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            $this->warn('No admin credentials found in .env file');
            
            $email = $this->ask('Enter admin email address');
            
            $validator = Validator::make(['email' => $email], [
                'email' => 'required|email'
            ]);
            
            if ($validator->fails()) {
                $this->error('Invalid email address');
                return $this->createAdminUser();
            }

            $password = $this->secret('Enter admin password (min 8 characters)');
            
            if (strlen($password) < 8) {
                $this->error('Password must be at least 8 characters');
                return $this->createAdminUser();
            }
        }

        // Delete existing admin user if forcing
        if ($this->option('force')) {
            User::where('email', $email)->delete();
        }

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->info("✅ Admin user created: {$email}");
    }
}
