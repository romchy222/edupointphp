<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Course $course)
    {
        $user = auth()->user();

        // Проверяем, не записан ли уже пользователь
        if ($course->isEnrolledBy($user)) {
            return back()->with('error', 'Вы уже записаны на этот курс!');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'paid_amount' => $course->price,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Вы успешно записались на курс!');
    }

    public function unenroll(Course $course)
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return back()->with('success', 'Вы отписались от курса!');
        }

        return back()->with('error', 'Вы не записаны на этот курс!');
    }
}
