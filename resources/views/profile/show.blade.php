@extends('layouts.app')

@section('title', 'Профиль - ' . $user->name)

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                <h4 class="mt-3">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
            </div>
        </div>

        <div class="list-group mt-3">
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
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-book"></i> Мои курсы</h5>
            </div>
            <div class="card-body">
                @if($user->enrolledCourses->count() > 0)
                    <div class="row">
                        @foreach($user->enrolledCourses as $course)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $course->title }}</h6>
                                        <p class="text-muted small">Преподаватель: {{ $course->teacher->name }}</p>
                                        @php
                                            $progress = $course->getProgressFor($user);
                                        @endphp
                                        <div class="progress mb-2" style="height: 20px;">
                                            <div class="progress-bar" style="width: {{ $progress }}%">{{ $progress }}%</div>
                                        </div>
                                        <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-primary">Продолжить</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Вы еще не записаны ни на один курс.</p>
                    <a href="{{ route('courses.index') }}" class="btn btn-primary">Просмотреть курсы</a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-award"></i> Сертификаты</h5>
            </div>
            <div class="card-body">
                @if($user->certificates->count() > 0)
                    <div class="list-group">
                        @foreach($user->certificates as $certificate)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $certificate->course->title }}</h6>
                                    <small class="text-muted">Выдан: {{ $certificate->issued_at->format('d.m.Y') }}</small>
                                </div>
                                <a href="{{ route('certificates.download', $certificate) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Скачать
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">У вас пока нет сертификатов.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
