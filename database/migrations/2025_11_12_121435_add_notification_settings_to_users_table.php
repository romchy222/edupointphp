<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(true)->after('role');
            $table->boolean('notify_new_lessons')->default(true)->after('email_notifications');
            $table->boolean('notify_deadlines')->default(true)->after('notify_new_lessons');
            $table->boolean('notify_comments')->default(true)->after('notify_deadlines');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_notifications', 'notify_new_lessons', 'notify_deadlines', 'notify_comments']);
        });
    }
};
