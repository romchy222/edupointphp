@extends('layouts.app')

@section('title', 'Избранные курсы - EduPoint')

@push('styles')
<style>
    .course-card {
        transition: all 0.3s;
        height: 100%;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-heart-fill text-danger"></i> Избранные курсы</h1>
        <p class="text-muted">Курсы, которые вы отметили как избранные</p>
    </div>
</div>

@if($favorites->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-heart text-muted" style="font-size: 5rem;"></i>
        <h3 class="mt-4">У вас пока нет избранных курсов</h3>
        <p class="text-muted">Добавляйте курсы в избранное, чтобы быстро находить их позже</p>
        <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">
            <i class="bi bi-search"></i> Найти курсы
        </a>
    </div>
@else
    <div class="row g-4">
        @foreach($favorites as $course)
            <div class="col-md-4">
                <div class="card course-card border-0 shadow-sm">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                             class="card-img-top" 
                             alt="{{ $course->title }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-gradient d-flex align-items-center justify-content-center" 
                             style="height: 200px; background: linear-gradient(135deg, {{ $course->category->color ?? '#667eea' }} 0%, {{ $course->category->color ?? '#764ba2' }} 100%);">
                            <i class="bi bi-book text-white" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="mb-2">
                            @if($course->category)
                                <span class="badge" style="background-color: {{ $course->category->color }}">
                                    <i class="bi {{ $course->category->icon }}"></i> {{ $course->category->name }}
                                </span>
                            @endif
                            @if($course->price == 0)
                                <span class="badge bg-success">Бесплатно</span>
                            @endif
                        </div>
                        
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text text-muted small">
                            {{ Str::limit($course->description, 100) }}
                        </p>
                        
                        @if($course->tags->count() > 0)
                            <div class="mb-3">
                                @foreach($course->tags->take(3) as $tag)
                                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $course->teacher->name }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-people"></i> {{ $course->enrollments_count }} студ.
                            </small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-primary flex-grow-1">
                                Подробнее <i class="bi bi-arrow-right"></i>
                            </a>
                            <form action="{{ route('favorites.toggle', $course) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" title="Удалить из избранного">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $favorites->links() }}
    </div>
@endif
@endsection
