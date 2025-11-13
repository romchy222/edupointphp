@extends('layouts.app')

@section('title', 'Рейтинги и достижения - EduPoint')

@push('styles')
<style>
    .leaderboard-card {
        transition: all 0.3s;
    }
    .leaderboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .rank-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .rank-1 { background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%); color: #333; }
    .rank-2 { background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%); color: #333; }
    .rank-3 { background: linear-gradient(135deg, #cd7f32 0%, #e09856 100%); color: white; }
    .rank-other { background: #e9ecef; color: #666; }
</style>
@endpush

@section('content')
<div class="text-center mb-5">
    <h1><i class="bi bi-trophy-fill text-warning"></i> Рейтинги и достижения</h1>
    <p class="lead text-muted">Лучшие студенты, преподаватели и популярные курсы</p>
</div>

<div class="row g-4">
    <!-- Топ студентов по сертификатам -->
    <div class="col-lg-6">
        <div class="card leaderboard-card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0"><i class="bi bi-award"></i> Топ по сертификатам</h5>
            </div>
            <div class="card-body p-0">
                @if($topStudents->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">Пока нет данных</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($topStudents as $index => $student)
                            <div class="list-group-item d-flex align-items-center">
                                <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }} me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $student->name }}</h6>
                                    <small class="text-muted">{{ $student->email }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-award-fill"></i> {{ $student->certificates_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Топ студентов по урокам -->
    <div class="col-lg-6">
        <div class="card leaderboard-card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="mb-0"><i class="bi bi-book-half"></i> Топ по урокам</h5>
            </div>
            <div class="card-body p-0">
                @if($topByLessons->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">Пока нет данных</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($topByLessons as $index => $student)
                            <div class="list-group-item d-flex align-items-center">
                                <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }} me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $student->name }}</h6>
                                    <small class="text-muted">{{ $student->email }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle-fill"></i> {{ $student->completed_lessons_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Популярные курсы -->
    <div class="col-lg-6">
        <div class="card leaderboard-card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h5 class="mb-0"><i class="bi bi-fire"></i> Популярные курсы</h5>
            </div>
            <div class="card-body p-0">
                @if($popularCourses->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">Пока нет данных</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($popularCourses as $index => $course)
                            <a href="{{ route('courses.show', $course) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }} me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Str::limit($course->title, 50) }}</h6>
                                    <small class="text-muted">{{ $course->teacher->name }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-info text-dark fs-6">
                                        <i class="bi bi-people-fill"></i> {{ $course->enrollments_count }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Лучшие преподаватели -->
    <div class="col-lg-6">
        <div class="card leaderboard-card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <h5 class="mb-0"><i class="bi bi-mortarboard-fill"></i> Лучшие преподаватели</h5>
            </div>
            <div class="card-body p-0">
                @if($topTeachers->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">Пока нет данных</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($topTeachers as $index => $teacher)
                            <div class="list-group-item d-flex align-items-center">
                                <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }} me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $teacher->name }}</h6>
                                    <small class="text-muted">{{ $teacher->email }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary fs-6">
                                        <i class="bi bi-book-fill"></i> {{ $teacher->teaching_courses_count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-5">
    <p class="text-muted">
        <i class="bi bi-info-circle"></i> Рейтинги обновляются ежедневно
    </p>
</div>
@endsection
