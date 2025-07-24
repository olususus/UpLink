<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('id');
            $table->string('group')->nullable()->after('order');
            $table->json('tags')->nullable()->after('group');
            $table->json('custom_fields')->nullable()->after('tags');
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['order', 'group', 'tags', 'custom_fields']);
        });
    }
};
