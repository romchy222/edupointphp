@extends('layouts.app')

@section('title', 'Результат теста - ' . $test->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-{{ $result->passed ? 'success' : 'danger' }} text-white">
                <h4 class="mb-0">
                    <i class="bi bi-{{ $result->passed ? 'check-circle' : 'x-circle' }}"></i>
                    {{ $result->passed ? 'Тест пройден!' : 'Тест не пройден' }}
                </h4>
            </div>
            <div class="card-body text-center">
                <h1 class="display-1">{{ $result->getPercentage() }}%</h1>
                <p class="lead">Правильных ответов: {{ $result->score }} из {{ $result->total_questions }}</p>
                
                <hr>

                <p><strong>Проходной балл:</strong> {{ $test->pass_score }}%</p>
                <p><strong>Дата прохождения:</strong> {{ $result->completed_at->format('d.m.Y H:i') }}</p>

                @if($result->passed)
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-trophy"></i> Поздравляем! Вы успешно прошли тест!
                    </div>
                @else
                    <div class="alert alert-danger mt-3">
                        <i class="bi bi-info-circle"></i> Вы можете пройти тест повторно.
                    </div>
                @endif

                <div class="mt-4">
                    @if(!$result->passed)
                        <a href="{{ route('tests.show', $test) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> Пройти еще раз
                        </a>
                    @endif
                    <a href="{{ route('courses.show', $test->course) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> К курсу
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
