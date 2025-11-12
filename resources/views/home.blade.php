@extends('layouts.app')

@section('title', 'EduPoint - Платформа онлайн курсов')

@push('styles')
<style>
    .hero-home {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 100px 0;
        margin: -2rem calc(-50vw + 50%) 3rem;
        position: relative;
        overflow: hidden;
    }
    .hero-home::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
        opacity: 0.5;
    }
    .hero-home .container {
        position: relative;
        z-index: 1;
    }
    .feature-card {
        transition: all 0.3s;
        border: none;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }
    .category-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: white;
    }
    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        margin: 3rem calc(-50vw + 50%);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-home text-center">
    <div class="container">
        <h1 class="display-2 fw-bold mb-4 animate__animated animate__fadeInDown">
            Откройте мир знаний с EduPoint
        </h1>
        <p class="lead mb-5 fs-4">
            Учитесь у лучших преподавателей, получайте навыки и развивайтесь в своем темпе
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('courses.index') }}" class="btn btn-light btn-lg px-5 py-3 shadow">
                <i class="bi bi-search"></i> Найти курс
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 py-3">
                    <i class="bi bi-person-plus"></i> Начать бесплатно
                </a>
            @endguest
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card feature-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-book-half text-primary" style="font-size: 3.5rem;"></i>
                <h2 class="mt-3 mb-0 fw-bold">{{ \App\Models\Course::where('status', 'published')->count() }}</h2>
                <p class="text-muted mb-0">Курсов доступно</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card feature-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-people text-success" style="font-size: 3.5rem;"></i>
                <h2 class="mt-3 mb-0 fw-bold">{{ \App\Models\User::count() }}</h2>
                <p class="text-muted mb-0">Активных студентов</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card feature-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-award text-warning" style="font-size: 3.5rem;"></i>
                <h2 class="mt-3 mb-0 fw-bold">{{ \App\Models\Certificate::count() }}</h2>
                <p class="text-muted mb-0">Выдано сертификатов</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card feature-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-star-fill text-info" style="font-size: 3.5rem;"></i>
                <h2 class="mt-3 mb-0 fw-bold">4.8</h2>
                <p class="text-muted mb-0">Средний рейтинг</p>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
@php
    $categories = \App\Models\Category::withCount('courses')->orderBy('name')->get();
@endphp

@if($categories->count() > 0)
<div class="mb-5">
    <h2 class="text-center mb-4"><i class="bi bi-grid-3x3-gap"></i> Популярные категории</h2>
    <div class="d-flex flex-wrap justify-content-center gap-3">
        @foreach($categories as $category)
            <a href="{{ route('courses.index', ['category' => $category->id]) }}" 
               class="category-badge shadow-sm" 
               style="background-color: {{ $category->color }}">
                <i class="bi {{ $category->icon }} me-2"></i>
                {{ $category->name }}
                <span class="badge bg-light text-dark ms-2">{{ $category->courses_count }}</span>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- Popular Courses -->
@php
    $popularCourses = \App\Models\Course::where('status', 'published')
        ->with(['teacher', 'category', 'tags'])
        ->withCount('enrollments')
        ->orderBy('enrollments_count', 'desc')
        ->take(6)
        ->get();
@endphp

@if($popularCourses->count() > 0)
<div class="mb-5">
    <h2 class="text-center mb-4">
        <i class="bi bi-fire text-danger"></i> Популярные курсы
    </h2>
    <div class="row g-4">
        @foreach($popularCourses as $course)
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card border-0 shadow-sm h-100">
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
                    <div class="card-body d-flex flex-column">
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
                        <p class="card-text text-muted small flex-grow-1">
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
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100">
                            Подробнее <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-lg">
            Смотреть все курсы <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>
@endif

<!-- Features Section -->
<div class="mb-5">
    <h2 class="text-center mb-5">Почему выбирают EduPoint?</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card feature-card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-lightning-charge text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h4>Быстрый старт</h4>
                    <p class="text-muted mb-0">
                        Начните обучение за минуты. Простая регистрация и интуитивный интерфейс
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-trophy text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4>Сертификаты</h4>
                    <p class="text-muted mb-0">
                        Получайте официальные сертификаты после успешного завершения курсов
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-clock-history text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h4>Учитесь в своем темпе</h4>
                    <p class="text-muted mb-0">
                        Доступ к материалам 24/7. Учитесь когда и где вам удобно
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
@guest
<div class="cta-section text-center">
    <div class="container">
        <h2 class="display-5 mb-4">Готовы начать обучение?</h2>
        <p class="lead mb-4">Присоединяйтесь к тысячам студентов уже сегодня</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 shadow">
                <i class="bi bi-person-plus"></i> Регистрация
            </a>
            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-5 py-3">
                <i class="bi bi-info-circle"></i> Узнать больше
            </a>
        </div>
    </div>
</div>
@endguest

@endsection
