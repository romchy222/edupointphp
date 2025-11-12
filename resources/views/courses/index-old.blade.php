@extends('layouts.app')

@section('title', 'Курсы - EduPoint')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-mortarboard"></i> Все курсы</h1>
    </div>
</div>

<div class="row">
    @forelse($courses as $course)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-book" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                    <p class="text-muted small">
                        <i class="bi bi-person"></i> {{ $course->teacher->name }}
                    </p>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary">{{ $course->price > 0 ? number_format($course->price, 2) . ' ₽' : 'Бесплатно' }}</span>
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Курсы пока не добавлены.
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $courses->links() }}
</div>
@endsection
