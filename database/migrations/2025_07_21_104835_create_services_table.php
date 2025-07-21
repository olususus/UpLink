<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('url')->nullable(); // URL for automatic monitoring
            $table->enum('type', ['automatic', 'manual']); // monitoring type
            $table->enum('status', ['operational', 'degraded', 'maintenance', 'outage'])->default('operational');
            $table->text('status_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('check_interval')->default(300); // seconds
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
