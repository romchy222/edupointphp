@extends('layouts.app')

@section('title', 'Статистика преподавателя - EduPoint')

@section('content')
<div class="container mt-4">
    <h2><i class="bi bi-graph-up"></i> Статистика преподавателя</h2>
    <p class="text-muted">Обзор вашей активности и курсов</p>

    <!-- Overall Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-book display-4 text-primary"></i>
                    <h3 class="mt-2">{{ $stats['total_courses'] }}</h3>
                    <p class="text-muted mb-0">Курсов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 text-success"></i>
                    <h3 class="mt-2">{{ $stats['total_students'] }}</h3>
                    <p class="text-muted mb-0">Студентов</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-play-circle display-4 text-info"></i>
                    <h3 class="mt-2">{{ $stats['total_lessons'] }}</h3>
                    <p class="text-muted mb-0">Уроков</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-award display-4 text-warning"></i>
                    <h3 class="mt-2">{{ $stats['total_certificates'] }}</h3>
                    <p class="text-muted mb-0">Сертификатов</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-activity"></i> Активность за последние 30 дней</h5>
        </div>
        <div class="card-body">
            <h3 class="text-center">{{ $recentEnrollments }}</h3>
            <p class="text-center text-muted mb-0">Новых студентов записались на ваши курсы</p>
        </div>
    </div>

    <!-- Popular Courses -->
    @if($popularCourses->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-fire"></i> Популярные курсы</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Курс</th>
                                <th class="text-center">Студентов</th>
                                <th class="text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popularCourses as $course)
                                <tr>
                                    <td>
                                        <strong>{{ $course->title }}</strong>
                                        @if($course->category)
                                            <br><small class="badge" style="background-color: {{ $course->category->color }}">
                                                {{ $course->category->name }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $course->enrollments_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('teacher.stats.course', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-bar-chart"></i> Подробная статистика
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- All Courses List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list"></i> Все курсы</h5>
        </div>
        <div class="card-body">
            @if($courses->isEmpty())
                <div class="alert alert-info">
                    У вас пока нет созданных курсов. <a href="{{ route('courses.create') }}">Создать первый курс</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th class="text-center">Студентов</th>
                                <th class="text-center">Статус</th>
                                <th class="text-center">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td class="text-center">{{ $course->enrollments_count }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $course->status === 'published' ? 'success' : 'secondary' }}">
                                            {{ $course->status === 'published' ? 'Опубликован' : 'Черновик' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('teacher.stats.course', $course->id) }}" class="btn btn-sm btn-primary">
                                            Статистика
                                        </a>
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">
                                            Редактировать
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
