<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('duration_hours')->nullable()->after('price')->comment('Course duration in hours');
            $table->string('level')->default('beginner')->after('duration_hours')->comment('beginner, intermediate, advanced');
            $table->text('requirements')->nullable()->after('level')->comment('Course requirements/prerequisites');
            $table->text('what_you_will_learn')->nullable()->after('requirements')->comment('Learning outcomes');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['duration_hours', 'level', 'requirements', 'what_you_will_learn']);
        });
    }
};
