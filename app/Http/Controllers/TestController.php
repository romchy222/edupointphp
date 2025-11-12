<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestController extends Controller
{
    public function show(Test $test): View
    {
        $test->load(['questions', 'course']);

        // Проверяем доступ
        if (!$test->course->isEnrolledBy(auth()->user())) {
            abort(403, 'Вы должны записаться на курс для прохождения теста.');
        }

        return view('tests.show', compact('test'));
    }

    public function submit(Request $request, Test $test)
    {
        $user = auth()->user();

        if (!$test->course->isEnrolledBy($user)) {
            abort(403);
        }

        $answers = $request->input('answers', []);
        $questions = $test->questions;

        $score = 0;
        foreach ($questions as $index => $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] == $question->correct_answer) {
                $score++;
            }
        }

        $percentage = ($score / $questions->count()) * 100;
        $passed = $percentage >= $test->pass_score;

        TestResult::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'score' => $score,
            'total_questions' => $questions->count(),
            'passed' => $passed,
            'answers' => $answers,
            'completed_at' => now(),
        ]);

        return redirect()->route('tests.result', $test)
            ->with('success', $passed ? 'Поздравляем! Вы прошли тест!' : 'К сожалению, вы не прошли тест.');
    }

    public function result(Test $test): View
    {
        $user = auth()->user();
        $result = $user->testResults()->where('test_id', $test->id)->latest()->first();

        if (!$result) {
            return redirect()->route('tests.show', $test);
        }

        return view('tests.result', compact('test', 'result'));
    }

    public function create(Course $course): View
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на создание тестов для этого курса');
        }
        
        return view('tests.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на создание тестов для этого курса');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pass_score' => 'required|integer|min:0|max:100',
        ]);

        $validated['course_id'] = $course->id;

        $test = Test::create($validated);

        return redirect()->route('tests.edit', $test)
            ->with('success', 'Тест создан! Теперь добавьте вопросы.');
    }

    public function edit(Test $test): View
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $test->course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого теста');
        }
        
        $test->load('questions');
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $test->course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого теста');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pass_score' => 'required|integer|min:0|max:100',
        ]);

        $test->update($validated);

        return back()->with('success', 'Тест обновлен!');
    }

    public function storeQuestion(Request $request, Test $test)
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $test->course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на добавление вопросов в этот тест');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'required|integer|min:0',
        ]);

        $validated['test_id'] = $test->id;
        $validated['order'] = $test->questions()->max('order') + 1;

        TestQuestion::create($validated);

        return back()->with('success', 'Вопрос добавлен!');
    }

    public function deleteQuestion(TestQuestion $question)
    {
        // Проверка прав: только преподаватель-владелец курса или админ
        if (auth()->user()->role !== 'admin' && $question->test->course->teacher_id !== auth()->id()) {
            abort(403, 'У вас нет прав на удаление вопросов из этого теста');
        }
        
        $question->delete();

        return back()->with('success', 'Вопрос удален!');
    }
}
