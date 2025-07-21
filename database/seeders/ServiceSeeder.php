<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'DBusWorld Website',
            'slug' => 'dbusworld-website',
            'description' => 'Main DBusWorld website and services',
            'url' => 'https://dbusworld.com',
            'type' => 'automatic',
            'status' => 'operational',
            'check_interval' => 300, // 5 minutes
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Game Server',
            'slug' => 'game-server',
            'description' => 'DBusWorld game server',
            'url' => null,
            'type' => 'manual',
            'status' => 'operational',
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Discord Bot',
            'slug' => 'discord-bot',
            'description' => 'DBusWorld Discord bot service',
            'url' => null,
            'type' => 'manual',
            'status' => 'operational',
            'is_active' => true,
        ]);
    }
}
