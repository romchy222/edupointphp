<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Только администраторы имеют доступ к этому разделу');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $tags = Tag::withCount('courses')->orderBy('name')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        // Slug генерируется автоматически в модели
        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Тег создан!');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        // Обновляем slug если изменилось имя
        if ($tag->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Тег обновлен!');
    }

    public function destroy(Tag $tag)
    {
        if ($tag->courses()->count() > 0) {
            return redirect()->route('admin.tags.index')
                ->with('error', 'Невозможно удалить тег, так как он используется в курсах');
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Тег удален!');
    }
}
