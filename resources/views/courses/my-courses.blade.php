@extends('layouts.app')

@section('title', 'Мои курсы - EduPoint')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-book-half"></i> Мои курсы</h1>
    </div>
    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
        <div class="col-auto">
            <a href="{{ route('courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Создать курс
            </a>
        </div>
    @endif
</div>

<div class="row">
    @forelse($courses as $course)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                         class="card-img-top" 
                         alt="{{ $course->title }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="bi bi-book" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>

                    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                        <div class="mb-2">
                            <span class="badge bg-{{ $course->status == 'published' ? 'success' : ($course->status == 'draft' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($course->status) }}
                            </span>
                            <span class="badge bg-info">{{ $course->enrollments->count() }} студентов</span>
                        </div>
                    @else
                        @php
                            $progress = $course->getProgressFor(auth()->user());
                        @endphp
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" 
                                 role="progressbar" 
                                 style="width: {{ $progress }}%">
                                {{ $progress }}%
                            </div>
                        </div>
                        @if(isset($course->pivot) && $course->pivot->enrolled_at)
                            <small class="text-muted">
                                Записан: {{ \Carbon\Carbon::parse($course->pivot->enrolled_at)->format('d.m.Y') }}
                            </small>
                        @endif
                    @endif
                </div>

                <div class="card-footer bg-white">
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Открыть
                    </a>

                    @if(auth()->user()->isTeacher() && $course->teacher_id == auth()->id())
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-gear"></i> Управление
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    У вас пока нет созданных курсов. <a href="{{ route('courses.create') }}">Создайте первый курс</a>
                @else
                    Вы еще не записаны ни на один курс. <a href="{{ route('courses.index') }}">Просмотреть доступные курсы</a>
                @endif
            </div>
        </div>
    @endforelse
</div>
@endsection
