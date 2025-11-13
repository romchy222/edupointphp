<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(true)->after('remember_token');
            $table->boolean('notify_new_lessons')->default(true)->after('email_notifications');
            $table->boolean('notify_comments')->default(true)->after('notify_new_lessons');
            $table->boolean('notify_progress')->default(true)->after('notify_comments');
            $table->boolean('notify_certificates')->default(true)->after('notify_progress');
            $table->boolean('notify_deadlines')->default(true)->after('notify_certificates');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications',
                'notify_new_lessons',
                'notify_comments',
                'notify_progress',
                'notify_certificates',
                'notify_deadlines',
            ]);
        });
    }
};
