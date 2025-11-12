<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson): View
    {
        // Проверка доступа
        if (!$lesson->is_free && !$course->isEnrolledBy(auth()->user())) {
            abort(403, 'Вы должны записаться на курс для просмотра этого урока.');
        }

        $lesson->load(['course', 'comments.user']);
        $isCompleted = $lesson->isCompletedBy(auth()->user());
        
        // Получаем следующий и предыдущий урок
        $nextLesson = $course->lessons()->where('order', '>', $lesson->order)->first();
        $prevLesson = $course->lessons()->where('order', '<', $lesson->order)->orderBy('order', 'desc')->first();

        return view('lessons.show', compact('lesson', 'course', 'isCompleted', 'nextLesson', 'prevLesson'));
    }

    public function complete(Lesson $lesson)
    {
        $user = auth()->user();

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        // Проверяем, завершен ли весь курс
        $course = $lesson->course;
        $totalLessons = $course->lessons()->count();
        $completedLessons = LessonProgress::whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->count();

        if ($totalLessons === $completedLessons) {
            $course->enrollments()->where('user_id', $user->id)->update([
                'completed_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Урок отмечен как завершенный!',
        ]);
    }

    public function create(Course $course): View
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на добавление уроков в этот курс');
        }
        
        return view('lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на добавление уроков в этот курс');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|max:10240',
            'is_free' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('lessons', 'public');
        }

        // Устанавливаем порядок
        $validated['order'] = $course->lessons()->max('order') + 1;
        $validated['course_id'] = $course->id;

        $lesson = Lesson::create($validated);

        // Отправка уведомлений студентам
        $enrolledStudents = $course->students;
        foreach ($enrolledStudents as $student) {
            $student->notify(new \App\Notifications\NewLessonPublished($lesson, $course));
        }

        return redirect()->route('courses.show', $course)
            ->with('success', 'Урок добавлен!');
    }

    public function edit(Lesson $lesson): View
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $lesson->course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого урока');
        }
        
        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $lesson->course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого урока');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|max:10240',
            'is_free' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('lessons', 'public');
        }

        $lesson->update($validated);

        return redirect()->route('lessons.show', [$lesson->course, $lesson])
            ->with('success', 'Урок обновлен!');
    }

    public function destroy(Lesson $lesson)
    {
        $course = $lesson->course;
        
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на удаление этого урока');
        }
        
        $lesson->delete();

        return redirect()->route('courses.show', $course)
            ->with('success', 'Урок удален!');
    }

    public function storeComment(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $lesson->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('lessons.show', [$lesson->course, $lesson])
            ->with('success', 'Комментарий добавлен!');
    }

    public function updateComment(Request $request, \App\Models\LessonComment $comment)
    {
        // Проверка прав: только автор или админ
        if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав на редактирование этого комментария');
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return redirect()->route('lessons.show', [$comment->lesson->course, $comment->lesson])
            ->with('success', 'Комментарий обновлен!');
    }

    public function destroyComment(\App\Models\LessonComment $comment)
    {
        // Проверка прав: только автор или админ
        if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав на удаление этого комментария');
        }

        $lesson = $comment->lesson;
        $course = $lesson->course;
        
        $comment->delete();

        return redirect()->route('lessons.show', [$course, $lesson])
            ->with('success', 'Комментарий удален!');
    }
}
