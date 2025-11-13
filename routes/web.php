<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Главная страница - список курсов
Route::get('/', [CourseController::class, 'index'])->name('home');

// Поиск
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

// Статичные страницы
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::post('/contact', [PageController::class, 'contact'])->name('contact.send');
Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');

// Маршруты для гостей
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email подтвержден!');
    })->middleware(['signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Ссылка для подтверждения отправлена!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Маршруты для авторизованных пользователей
// Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {
    // Выход
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Профиль
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications');

    // Мои курсы
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my');

    // Запись на курс
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/unenroll', [EnrollmentController::class, 'unenroll'])->name('courses.unenroll');

    // Отметка урока как завершенного
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    // Комментарии к урокам
    Route::post('/lessons/{lesson}/comments', [LessonController::class, 'storeComment'])->name('lessons.comments.store');
    Route::put('/comments/{comment}', [LessonController::class, 'updateComment'])->name('lessons.comments.update');
    Route::delete('/comments/{comment}', [LessonController::class, 'destroyComment'])->name('lessons.comments.destroy');

    // Уведомления
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/settings', [\App\Http\Controllers\NotificationController::class, 'settings'])->name('notifications.settings');
    Route::post('/notifications/settings', [\App\Http\Controllers\NotificationController::class, 'updateSettings'])->name('notifications.update-settings');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Тесты
    Route::get('/tests/{test}', [TestController::class, 'show'])->name('tests.show');
    Route::post('/tests/{test}/submit', [TestController::class, 'submit'])->name('tests.submit');
    Route::get('/tests/{test}/result', [TestController::class, 'result'])->name('tests.result');

    // Сертификаты
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/courses/{course}/certificate', [CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'view'])->name('certificates.view');

    // Отзывы
    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Избранное
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/courses/{course}/favorite', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Скачивание материалов урока
    Route::get('/lessons/{lesson}/attachments/{attachment}/download', [LessonController::class, 'downloadAttachment'])->name('lessons.attachment.download');

    // Статистика для преподавателей
    Route::get('/teacher/stats', [\App\Http\Controllers\TeacherStatsController::class, 'index'])->name('teacher.stats.index');
    Route::get('/teacher/stats/course/{course}', [\App\Http\Controllers\TeacherStatsController::class, 'courseStats'])->name('teacher.stats.course');

    // Управление курсами (для преподавателей)
    Route::middleware('can:create,App\Models\Course')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    });

    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Управление уроками
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

    // Управление тестами
    Route::get('/courses/{course}/tests/create', [TestController::class, 'create'])->name('tests.create');
    Route::post('/courses/{course}/tests', [TestController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::post('/tests/{test}/questions', [TestController::class, 'storeQuestion'])->name('tests.questions.store');
    Route::delete('/questions/{question}', [TestController::class, 'deleteQuestion'])->name('tests.questions.destroy');

    // Админ-панель
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Пользователи
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Курсы
        Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
        Route::put('/courses/{course}/status', [AdminController::class, 'updateCourseStatus'])->name('courses.status');
        
        // Категории
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        
        // Теги
        Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show']);
        
        // Заявки
        Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
        Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('messages.show');
        Route::put('/messages/{message}/status', [AdminController::class, 'updateMessageStatus'])->name('messages.status');
        Route::post('/messages/{message}/reply', [AdminController::class, 'replyMessage'])->name('messages.reply');
        
        // Отзывы
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        
        // Настройки
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        
        // Статистика
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    });
});

// Публичные страницы курсов и уроков
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
