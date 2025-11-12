<?php

namespace App\Providers;

use App\Models\Course;
use App\Policies\CoursePolicy;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Регистрация CertificateService как singleton
        $this->app->singleton(CertificateService::class, function ($app) {
            return new CertificateService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрация политик
        Gate::policy(Course::class, CoursePolicy::class);

        // Использование Bootstrap для пагинации
        Paginator::useBootstrapFive();
    }
}
