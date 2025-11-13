<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        // Топ студентов по количеству завершенных курсов
        $topStudents = User::where('role', 'student')
            ->withCount('certificates')
            ->having('certificates_count', '>', 0)
            ->orderBy('certificates_count', 'desc')
            ->take(10)
            ->get();

        // Топ студентов по количеству завершенных уроков
        $topByLessons = User::where('role', 'student')
            ->withCount('completedLessons')
            ->having('completed_lessons_count', '>', 0)
            ->orderBy('completed_lessons_count', 'desc')
            ->take(10)
            ->get();

        // Самые популярные курсы
        $popularCourses = Course::where('status', 'published')
            ->withCount('enrollments')
            ->having('enrollments_count', '>', 0)
            ->orderBy('enrollments_count', 'desc')
            ->take(10)
            ->get();

        // Лучшие преподаватели
        $topTeachers = User::whereIn('role', ['teacher', 'admin'])
            ->withCount(['teachingCourses' => function($q) {
                $q->where('status', 'published');
            }])
            ->having('teaching_courses_count', '>', 0)
            ->orderBy('teaching_courses_count', 'desc')
            ->take(10)
            ->get();

        return view('leaderboard.index', compact('topStudents', 'topByLessons', 'popularCourses', 'topTeachers'));
    }
}
