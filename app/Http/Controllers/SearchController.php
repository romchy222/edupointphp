<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'all'); // all, courses, lessons
        $category = $request->input('category');
        $tag = $request->input('tag');

        $results = [
            'courses' => collect(),
            'lessons' => collect(),
        ];

        if (!empty($query)) {
            // Save search history for authenticated users
            if (auth()->check()) {
                $this->saveSearchHistory($query);
            }

            if ($type === 'all' || $type === 'courses') {
                $coursesQuery = Course::where('status', 'published')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    });

                if ($category) {
                    $coursesQuery->where('category_id', $category);
                }

                if ($tag) {
                    $coursesQuery->whereHas('tags', function ($q) use ($tag) {
                        $q->where('tags.id', $tag);
                    });
                }

                $results['courses'] = $coursesQuery
                    ->with(['category', 'tags', 'teacher'])
                    ->take(20)
                    ->get();
            }

            if ($type === 'all' || $type === 'lessons') {
                $lessonsQuery = Lesson::whereHas('course', function ($q) {
                        $q->where('status', 'published');
                    })
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('content', 'LIKE', "%{$query}%");
                    });

                $results['lessons'] = $lessonsQuery
                    ->with(['course'])
                    ->take(20)
                    ->get();
            }
        }

        // Popular searches
        $popularSearches = $this->getPopularSearches();

        // User's search history
        $searchHistory = auth()->check() ? $this->getUserSearchHistory() : [];

        $categories = Category::all();
        $tags = Tag::all();

        return view('search.index', compact('results', 'query', 'type', 'popularSearches', 'searchHistory', 'categories', 'tags', 'category', 'tag'));
    }

    private function saveSearchHistory($query)
    {
        $history = session()->get('search_history', []);
        
        // Remove if already exists
        $history = array_filter($history, fn($item) => $item !== $query);
        
        // Add to beginning
        array_unshift($history, $query);
        
        // Keep only last 10
        $history = array_slice($history, 0, 10);
        
        session()->put('search_history', $history);
    }

    private function getUserSearchHistory()
    {
        return session()->get('search_history', []);
    }

    private function getPopularSearches()
    {
        // In a real app, this would come from a database table tracking searches
        // For now, return some sample popular searches
        return [
            'PHP',
            'Laravel',
            'JavaScript',
            'Python',
            'Web Development',
        ];
    }
}
