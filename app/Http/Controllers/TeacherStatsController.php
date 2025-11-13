<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherStatsController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();
        
        if (!$teacher->isTeacher() && !$teacher->isAdmin()) {
            abort(403, 'У вас нет доступа к статистике преподавателя');
        }

        // Get teacher's courses
        $courses = Course::where('teacher_id', $teacher->id)
            ->withCount('enrollments')
            ->get();

        // Overall statistics
        $stats = [
            'total_courses' => $courses->count(),
            'total_students' => $courses->sum('enrollments_count'),
            'total_lessons' => Lesson::whereIn('course_id', $courses->pluck('id'))->count(),
            'total_certificates' => DB::table('certificates')
                ->whereIn('course_id', $courses->pluck('id'))
                ->count(),
        ];

        // Popular courses
        $popularCourses = $courses->sortByDesc('enrollments_count')->take(5);

        // Recent activity (last 30 days)
        $recentEnrollments = DB::table('enrollments')
            ->whereIn('course_id', $courses->pluck('id'))
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return view('teacher.stats.index', compact('stats', 'courses', 'popularCourses', 'recentEnrollments'));
    }

    public function courseStats($courseId)
    {
        $teacher = auth()->user();
        $course = Course::with(['enrollments.user', 'lessons', 'tests'])
            ->findOrFail($courseId);

        // Check if teacher owns this course
        if ($course->teacher_id !== $teacher->id && !$teacher->isAdmin()) {
            abort(403, 'У вас нет доступа к этому курсу');
        }

        // Student progress
        $studentsProgress = [];
        foreach ($course->enrollments as $enrollment) {
            $progress = $course->getProgressFor($enrollment->user);
            $completedLessons = DB::table('lesson_progress')
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->where('user_id', $enrollment->user_id)
                ->where('completed', true)
                ->count();

            $studentsProgress[] = [
                'user' => $enrollment->user,
                'progress' => $progress,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $course->lessons->count(),
                'enrolled_at' => $enrollment->created_at,
            ];
        }

        // Sort by progress desc
        usort($studentsProgress, fn($a, $b) => $b['progress'] <=> $a['progress']);

        // Lesson popularity (views/completions)
        $lessonStats = [];
        foreach ($course->lessons as $lesson) {
            $views = DB::table('lesson_progress')
                ->where('lesson_id', $lesson->id)
                ->count();
            
            $completions = DB::table('lesson_progress')
                ->where('lesson_id', $lesson->id)
                ->where('completed', true)
                ->count();

            $lessonStats[] = [
                'lesson' => $lesson,
                'views' => $views,
                'completions' => $completions,
                'completion_rate' => $views > 0 ? round(($completions / $views) * 100) : 0,
            ];
        }

        // Test statistics
        $testStats = [];
        foreach ($course->tests as $test) {
            $attempts = DB::table('test_attempts')
                ->where('test_id', $test->id)
                ->get();

            $passed = $attempts->where('passed', true)->count();
            $avgScore = $attempts->avg('score');

            $testStats[] = [
                'test' => $test,
                'total_attempts' => $attempts->count(),
                'passed' => $passed,
                'failed' => $attempts->count() - $passed,
                'avg_score' => round($avgScore, 1),
            ];
        }

        // Enrollment trend (last 30 days)
        $enrollmentTrend = DB::table('enrollments')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('course_id', $courseId)
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('teacher.stats.course', compact(
            'course',
            'studentsProgress',
            'lessonStats',
            'testStats',
            'enrollmentTrend'
        ));
    }
}
