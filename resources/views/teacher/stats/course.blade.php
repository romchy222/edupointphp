@extends('layouts.app')

@section('title', 'Статистика курса - EduPoint')

@push('styles')
<style>
    .progress-cell {
        min-width: 150px;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-bar-chart"></i> Статистика курса</h2>
            <h4 class="text-muted">{{ $course->title }}</h4>
        </div>
        <a href="{{ route('teacher.stats.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Назад к обзору
        </a>
    </div>

    <!-- Course Overview -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-people display-5 text-primary"></i>
                    <h3 class="mt-2">{{ $course->enrollments->count() }}</h3>
                    <p class="text-muted mb-0">Студентов записано</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-play-circle display-5 text-info"></i>
                    <h3 class="mt-2">{{ $course->lessons->count() }}</h3>
                    <p class="text-muted mb-0">Уроков в курсе</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-star display-5 text-warning"></i>
                    <h3 class="mt-2">{{ $course->averageRating() }}</h3>
                    <p class="text-muted mb-0">Средний рейтинг</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Progress -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Прогресс студентов</h5>
        </div>
        <div class="card-body">
            @if(count($studentsProgress) === 0)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Пока нет записавшихся студентов
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Студент</th>
                                <th>Прогресс</th>
                                <th class="text-center">Завершено уроков</th>
                                <th class="text-center">Дата записи</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsProgress as $student)
                                <tr>
                                    <td>
                                        <i class="bi bi-person-circle"></i> {{ $student['user']->name }}
                                    </td>
                                    <td class="progress-cell">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                <div class="progress-bar bg-{{ $student['progress'] == 100 ? 'success' : 'primary' }}" 
                                                     style="width: {{ $student['progress'] }}%">
                                                    {{ $student['progress'] }}%
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            {{ $student['completed_lessons'] }} / {{ $student['total_lessons'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            {{ $student['enrolled_at']->format('d.m.Y') }}
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Lesson Statistics -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Статистика по урокам</h5>
        </div>
        <div class="card-body">
            @if(count($lessonStats) === 0)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> В курсе пока нет уроков
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Урок</th>
                                <th class="text-center">Просмотров</th>
                                <th class="text-center">Завершений</th>
                                <th class="text-center">% завершения</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lessonStats as $stat)
                                <tr>
                                    <td>
                                        <strong>{{ $stat['lesson']->title }}</strong>
                                        <br><small class="text-muted">{{ $stat['lesson']->duration }} мин</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $stat['views'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $stat['completions'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px; min-width: 100px;">
                                            <div class="progress-bar" style="width: {{ $stat['completion_rate'] }}%">
                                                {{ $stat['completion_rate'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Test Statistics -->
    @if(count($testStats) > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Статистика по тестам</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Тест</th>
                                <th class="text-center">Попыток</th>
                                <th class="text-center">Сдано</th>
                                <th class="text-center">Не сдано</th>
                                <th class="text-center">Средний балл</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testStats as $stat)
                                <tr>
                                    <td><strong>{{ $stat['test']->title }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $stat['total_attempts'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $stat['passed'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $stat['failed'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $stat['avg_score'] }}%</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Enrollment Trend -->
    @if($enrollmentTrend->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Тренд записей (последние 30 дней)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th class="text-center">Новых студентов</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollmentTrend as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d.m.Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $day->count }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
