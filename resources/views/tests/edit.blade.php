@extends('layouts.app')

@section('title', 'Редактировать тест - ' . $test->title)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5>Настройки теста</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tests.update', $test) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Название *</label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $test->title) }}" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $test->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="pass_score" class="form-label">Проходной балл (%)</label>
                        <input type="number" 
                               class="form-control" 
                               id="pass_score" 
                               name="pass_score" 
                               value="{{ old('pass_score', $test->pass_score) }}" 
                               min="0" 
                               max="100" 
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Сохранить
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Вопросы ({{ $test->questions->count() }})</h5>
            </div>
            <div class="card-body">
                @forelse($test->questions as $index => $question)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6>Вопрос {{ $index + 1 }}</h6>
                                <form method="POST" action="{{ route('tests.questions.destroy', $question) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить вопрос?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p><strong>{{ $question->question }}</strong></p>
                            <ul class="mb-0">
                                @foreach($question->options as $i => $option)
                                    <li class="{{ $i == $question->correct_answer ? 'text-success fw-bold' : '' }}">
                                        {{ $option }} {{ $i == $question->correct_answer ? '✓' : '' }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Вопросов пока нет</p>
                @endforelse

                <hr>

                <h5>Добавить вопрос</h5>
                <form method="POST" action="{{ route('tests.questions.store', $test) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="question" class="form-label">Текст вопроса *</label>
                        <textarea class="form-control" 
                                  id="question" 
                                  name="question" 
                                  rows="2" 
                                  required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Варианты ответов *</label>
                        @for($i = 0; $i < 4; $i++)
                            <input type="text" 
                                   class="form-control mb-2" 
                                   name="options[]" 
                                   placeholder="Вариант {{ $i + 1 }}" 
                                   required>
                        @endfor
                    </div>

                    <div class="mb-3">
                        <label for="correct_answer" class="form-label">Правильный ответ *</label>
                        <select class="form-select" id="correct_answer" name="correct_answer" required>
                            <option value="0">Вариант 1</option>
                            <option value="1">Вариант 2</option>
                            <option value="2">Вариант 3</option>
                            <option value="3">Вариант 4</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus"></i> Добавить вопрос
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('courses.show', $test->course) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Вернуться к курсу
            </a>
        </div>
    </div>
</div>
@endsection
