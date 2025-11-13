<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = auth()->user()
            ->favoriteCourses()
            ->with(['teacher', 'category', 'tags'])
            ->withCount('enrollments')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Course $course): RedirectResponse
    {
        $user = auth()->user();

        if ($course->isFavoritedBy($user)) {
            $user->favoriteCourses()->detach($course->id);
            return back()->with('success', 'Курс удален из избранного');
        }

        $user->favoriteCourses()->attach($course->id);
        return back()->with('success', 'Курс добавлен в избранное');
    }
}
