@extends('layouts.app')

@section('title', $test->title . ' - Тест')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">{{ $test->title }}</h4>
                <p class="mb-0 text-muted">{{ $test->course->title }}</p>
            </div>
            <div class="card-body">
                @if($test->description)
                    <div class="alert alert-info">
                        {{ $test->description }}
                    </div>
                @endif

                <div class="alert alert-warning">
                    <strong>Проходной балл:</strong> {{ $test->pass_score }}%<br>
                    <strong>Вопросов:</strong> {{ $test->questions->count() }}
                </div>

                <form method="POST" action="{{ route('tests.submit', $test) }}">
                    @csrf

                    @foreach($test->questions as $index => $question)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Вопрос {{ $index + 1 }}</h5>
                                <p class="card-text">{{ $question->question }}</p>

                                @foreach($question->options as $optionIndex => $option)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="question{{ $question->id }}_option{{ $optionIndex }}" 
                                               value="{{ $optionIndex }}" 
                                               required>
                                        <label class="form-check-label" for="question{{ $question->id }}_option{{ $optionIndex }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.show', $test->course) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Назад к курсу
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Отправить ответы
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
