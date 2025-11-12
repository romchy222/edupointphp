@extends('layouts.app')

@section('title', 'Профиль - ' . $user->name)

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        font-size: 5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body text-center">
                <div class="profile-avatar mb-3">
                    <i class="bi bi-person"></i>
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                @php
                    $roleBadge = match($user->role) {
                        'admin' => ['class' => 'danger', 'icon' => 'shield-fill', 'text' => 'Администратор'],
                        'teacher' => ['class' => 'primary', 'icon' => 'mortarboard-fill', 'text' => 'Преподаватель'],
                        default => ['class' => 'secondary', 'icon' => 'person-fill', 'text' => 'Студент'],
                    };
                @endphp
                <span class="badge bg-{{ $roleBadge['class'] }}">
                    <i class="bi bi-{{ $roleBadge['icon'] }}"></i> {{ $roleBadge['text'] }}
                </span>
                <div class="mt-3 text-muted small">
                    <i class="bi bi-calendar3"></i> Зарегистрирован {{ $user->created_at->format('d.m.Y') }}
                </div>
            </div>
        </div>

        <div class="list-group shadow-sm">
            <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action active">
                <i class="bi bi-person"></i> Профиль
            </a>
            <a href="{{ route('courses.my') }}" class="list-group-item list-group-item-action">
                <i class="bi bi-book"></i> Мои курсы
            </a>
            <a href="{{ route('certificates.index') }}" class="list-group-item list-group-item-action">
                <i class="bi bi-award"></i> Сертификаты
            </a>
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                <i class="bi bi-gear"></i> Настройки
            </a>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-book-half text-primary" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $user->enrolledCourses->count() }}</h3>
                        <p class="text-muted small mb-0">Курсов</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-award text-warning" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $user->certificates->count() }}</h3>
                        <p class="text-muted small mb-0">Сертификатов</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                        @php
                            $completedLessons = 0;
                            foreach($user->enrolledCourses as $course) {
                                $completedLessons += $user->completedLessons()->whereIn('lesson_id', $course->lessons->pluck('id'))->count();
                            }
                        @endphp
                        <h3 class="mt-2 mb-0">{{ $completedLessons }}</h3>
                        <p class="text-muted small mb-0">Уроков пройдено</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-star-fill text-info" style="font-size: 2.5rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $user->reviews->count() }}</h3>
                        <p class="text-muted small mb-0">Отзывов</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-book"></i> Мои курсы</h5>
            </div>
            <div class="card-body">
                @if($user->enrolledCourses->count() > 0)
                    <div class="row g-3">
                        @foreach($user->enrolledCourses->take(4) as $course)
                            <div class="col-md-6">
                                <div class="card h-100 border">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                             class="card-img-top" 
                                             alt="{{ $course->title }}"
                                             style="height: 150px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $course->title }}</h6>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-person"></i> {{ $course->teacher->name }}
                                        </p>
                                        @php
                                            $progress = $course->getProgressFor($user);
                                        @endphp
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="text-muted">Прогресс</span>
                                                <span class="fw-bold">{{ $progress }}%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-play-circle"></i> Продолжить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($user->enrolledCourses->count() > 4)
                        <div class="text-center mt-3">
                            <a href="{{ route('courses.my') }}" class="btn btn-outline-primary">
                                Смотреть все курсы <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">Вы еще не записаны ни на один курс</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">
                            <i class="bi bi-search"></i> Найти курсы
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Certificates -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-award"></i> Последние сертификаты</h5>
            </div>
            <div class="card-body">
                @if($user->certificates->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($user->certificates->take(5) as $certificate)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning text-white rounded p-2 me-3">
                                        <i class="bi bi-award-fill" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $certificate->course->title }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3"></i> 
                                            {{ $certificate->issued_at->format('d.m.Y') }}
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('certificates.view', $certificate) }}" 
                                       class="btn btn-sm btn-outline-primary me-2"
                                       target="_blank">
                                        <i class="bi bi-eye"></i> Просмотр
                                    </a>
                                    <a href="{{ route('certificates.download', $certificate) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($user->certificates->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('certificates.index') }}" class="btn btn-outline-primary">
                                Смотреть все сертификаты <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-award text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">У вас пока нет сертификатов</p>
                        <p class="small text-muted">Завершите курс на 100%, чтобы получить сертификат</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
