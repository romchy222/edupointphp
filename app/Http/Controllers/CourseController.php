<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::where('status', 'published')->with('teacher');

        // Поиск
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Фильтр по цене
        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where('price', 0);
            } elseif ($request->price === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Фильтр по преподавателю
        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->teacher);
        }

        // Сортировка
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $courses = $query->paginate(12)->withQueryString();

        // Статистика для главной
        $stats = [
            'total_courses' => Course::where('status', 'published')->count(),
            'total_students' => \App\Models\Enrollment::distinct('user_id')->count(),
            'total_certificates' => \App\Models\Certificate::count(),
        ];

        // Популярные курсы
        $popularCourses = Course::where('status', 'published')
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(3)
            ->get();

        // Список преподавателей для фильтра
        $teachers = \App\Models\User::where('role', 'teacher')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('courses.index', compact('courses', 'stats', 'popularCourses', 'teachers'));
    }

    public function show(Course $course): View
    {
        $course->load(['teacher', 'lessons', 'tests', 'reviews.user']);
        
        $isEnrolled = false;
        $progress = 0;
        $hasReviewed = false;

        if (auth()->check()) {
            $isEnrolled = $course->isEnrolledBy(auth()->user());
            if ($isEnrolled) {
                $progress = $course->getProgressFor(auth()->user());
            }
            $hasReviewed = $course->reviews()->where('user_id', auth()->id())->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled', 'progress', 'hasReviewed'));
    }

    public function myCourses(): View
    {
        $user = auth()->user();
        
        if ($user->isTeacher()) {
            $courses = $user->teachingCourses()->with('enrollments')->get();
        } else {
            $courses = $user->enrolledCourses()->withPivot('enrolled_at')->get();
        }

        return view('courses.my-courses', compact('courses'));
    }

    public function create(): View
    {
        // Только преподаватели и админы могут создавать курсы
        if (!in_array(auth()->user()->role, ['teacher', 'admin'])) {
            abort(403, 'У вас нет прав на создание курсов');
        }
        
        return view('courses.create');
    }

    public function store(Request $request)
    {
        // Только преподаватели и админы могут создавать курсы
        if (!in_array(auth()->user()->role, ['teacher', 'admin'])) {
            abort(403, 'У вас нет прав на создание курсов');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['teacher_id'] = auth()->id();
        $validated['status'] = 'draft';

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course = Course::create($validated);

        // Прикрепляем теги
        if ($request->filled('tags')) {
            $course->tags()->attach($request->tags);
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Курс успешно создан!');
    }

    public function edit(Course $course): View
    {
        // Проверка прав: только преподаватель-владелец или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого курса');
        }
        
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // Проверка прав: только преподаватель-владелец или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого курса');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($validated);

        // Синхронизируем теги
        if ($request->has('tags')) {
            $course->tags()->sync($request->tags);
        } else {
            $course->tags()->detach();
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Курс обновлен!');
    }

    public function destroy(Course $course)
    {
        // Проверка прав: только преподаватель-владелец или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на удаление этого курса');
        }
        
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Курс удален!');
    }
}
