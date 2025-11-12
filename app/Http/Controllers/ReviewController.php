<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course)
    {
        // Проверка: пользователь должен быть записан на курс
        if (!$course->isEnrolledBy(auth()->user())) {
            return back()->with('error', 'Вы можете оставить отзыв только на курсы, на которые записаны.');
        }

        // Проверка: пользователь еще не оставлял отзыв
        $existingReview = Review::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Вы уже оставили отзыв на этот курс.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Спасибо за ваш отзыв!');
    }

    public function destroy(Review $review)
    {
        // Только автор или админ могут удалить отзыв
        if (auth()->id() !== $review->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Отзыв удален.');
    }
}
