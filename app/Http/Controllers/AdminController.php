<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        // Проверка прав администратора
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        // Общая статистика
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_enrollments' => Enrollment::count(),
            'total_certificates' => Certificate::count(),
            'total_reviews' => Review::count(),
            'new_messages' => ContactMessage::where('status', 'new')->count(),
        ];

        // Статистика за последние 30 дней
        $last30Days = now()->subDays(30);
        $recentStats = [
            'new_users' => User::where('created_at', '>=', $last30Days)->count(),
            'new_courses' => Course::where('created_at', '>=', $last30Days)->count(),
            'new_enrollments' => Enrollment::where('created_at', '>=', $last30Days)->count(),
            'new_reviews' => Review::where('created_at', '>=', $last30Days)->count(),
        ];

        // Графики - регистрации по дням за последние 7 дней
        $registrationChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $registrationChart['labels'][] = now()->subDays($i)->format('d.m');
            $registrationChart['data'][] = User::whereDate('created_at', $date)->count();
        }

        // Популярные курсы
        $popularCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(10)->get();
        $recentCourses = Course::with('teacher')->latest()->take(5)->get();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentStats', 
            'registrationChart', 
            'popularCourses',
            'recentUsers', 
            'recentCourses',
            'recentMessages'
        ));
    }

    public function users(): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $validated = $request->validate([
            'role' => 'required|in:student,teacher,admin',
        ]);

        $user->update($validated);

        return back()->with('success', 'Роль пользователя обновлена!');
    }

    public function deleteUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить свой аккаунт!');
        }

        $user->delete();
        return back()->with('success', 'Пользователь удален!');
    }

    public function courses(): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $courses = Course::with('teacher')->latest()->paginate(20);
        return view('admin.courses', compact('courses'));
    }

    public function updateCourseStatus(Request $request, Course $course)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,published,archived',
        ]);

        $course->update($validated);

        return back()->with('success', 'Статус курса обновлен!');
    }

    // Управление заявками
    public function messages(): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.messages', compact('messages'));
    }

    public function showMessage(ContactMessage $message): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        return view('admin.message-show', compact('message'));
    }

    public function updateMessageStatus(Request $request, ContactMessage $message)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
        ]);

        $message->update($validated);

        return back()->with('success', 'Статус заявки обновлен!');
    }

    public function replyMessage(Request $request, ContactMessage $message)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $validated = $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $message->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => 'resolved',
            'replied_at' => now(),
        ]);

        // Здесь можно отправить email пользователю

        return back()->with('success', 'Ответ отправлен!');
    }

    // Управление отзывами
    public function reviews(): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $reviews = Review::with(['user', 'course'])->latest()->paginate(20);
        return view('admin.reviews', compact('reviews'));
    }

    // Настройки платформы
    public function settings(): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $settingsCollection = Setting::all();
        $settings = [];
        
        foreach ($settingsCollection as $setting) {
            $settings[$setting->group][$setting->key] = $setting->value;
        }

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        foreach ($request->except('_token', '_method') as $group => $settings) {
            if (is_array($settings)) {
                foreach ($settings as $key => $value) {
                    Setting::updateOrCreate(
                        ['key' => $key, 'group' => $group],
                        ['value' => $value ?? '', 'type' => 'text', 'group' => $group]
                    );
                }
            }
        }

        return back()->with('success', 'Настройки успешно сохранены!');
    }

    // Статистика
    public function statistics(): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещен.');
        }

        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'enrollments' => Enrollment::count(),
            'certificates' => Certificate::count(),
        ];

        // График регистраций за 30 дней
        $registrationsData = ['labels' => [], 'data' => []];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $registrationsData['labels'][] = now()->subDays($i)->format('d.m');
            $registrationsData['data'][] = User::whereDate('created_at', $date)->count();
        }

        // График записей на курсы за 30 дней
        $enrollmentsData = ['labels' => [], 'data' => []];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $enrollmentsData['labels'][] = now()->subDays($i)->format('d.m');
            $enrollmentsData['data'][] = Enrollment::whereDate('created_at', $date)->count();
        }

        // Топ-10 курсов с рейтингами
        $topCourses = Course::withCount('enrollments')
            ->with('teacher')
            ->withAvg('reviews', 'rating')
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($course) {
                $course->average_rating = $course->reviews_avg_rating;
                return $course;
            });

        // Распределение пользователей по ролям
        $rolesData = [
            'labels' => ['Студенты', 'Преподаватели', 'Админы'],
            'data' => [
                User::where('role', 'student')->count(),
                User::where('role', 'teacher')->count(),
                User::where('role', 'admin')->count(),
            ]
        ];

        // Активность по дням недели (последние 30 дней)
        $activityData = [
            'labels' => ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
            'data' => []
        ];
        
        for ($day = 1; $day <= 7; $day++) {
            $count = Enrollment::whereBetween('created_at', [now()->subDays(30), now()])
                ->whereRaw('DAYOFWEEK(created_at) = ?', [$day == 7 ? 1 : $day + 1])
                ->count();
            $activityData['data'][] = $count;
        }

        // Последняя активность (записи на курсы)
        $recentActivity = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($enrollment) {
                return [
                    'user' => $enrollment->user->name,
                    'action' => 'Записался на курс "' . $enrollment->course->title . '"',
                    'date' => $enrollment->created_at->format('d.m.Y H:i')
                ];
            });

        return view('admin.statistics', compact(
            'stats', 
            'registrationsData', 
            'enrollmentsData', 
            'topCourses', 
            'rolesData', 
            'activityData', 
            'recentActivity'
        ));
    }
}
