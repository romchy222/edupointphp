@extends('layouts.app')

@section('title', 'Каталог курсов - EduPoint')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        margin: -2rem calc(-50vw + 50%) 3rem;
    }
    .stat-card {
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .course-card {
        transition: all 0.3s;
        height: 100%;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .course-card img {
        height: 200px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Учитесь в своем темпе</h1>
        <p class="lead mb-4">Лучшие онлайн-курсы от опытных преподавателей</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#courses" class="btn btn-light btn-lg">
                <i class="bi bi-book"></i> Смотреть курсы
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-person-plus"></i> Зарегистрироваться
                </a>
            @endguest
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card stat-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-book-half text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-3 mb-0">{{ $stats['total_courses'] }}</h3>
                <p class="text-muted">Курсов</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-people text-success" style="font-size: 3rem;"></i>
                <h3 class="mt-3 mb-0">{{ $stats['total_students'] }}</h3>
                <p class="text-muted">Студентов</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-center border-0 shadow-sm">
            <div class="card-body py-4">
                <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                <h3 class="mt-3 mb-0">{{ $stats['total_certificates'] }}</h3>
                <p class="text-muted">Сертификатов</p>
            </div>
        </div>
    </div>
</div>

<!-- Popular Courses -->
@if($popularCourses->count() > 0)
<div class="mb-5">
    <h2 class="mb-4"><i class="bi bi-star-fill text-warning"></i> Популярные курсы</h2>
    <div class="row g-4">
        @foreach($popularCourses as $course)
            <div class="col-md-4">
                <div class="card course-card border-0 shadow-sm">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                    @else
                        <div class="bg-gradient" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    @endif
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-fire"></i> Популярный
                            </span>
                            @if($course->category)
                                <span class="badge" style="background-color: {{ $course->category->color }}">
                                    <i class="bi {{ $course->category->icon }}"></i> {{ $course->category->name }}
                                </span>
                            @endif
                        </div>
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($course->description, 100) }}</p>
                        @if($course->tags->count() > 0)
                            <div class="mb-2">
                                @foreach($course->tags->take(3) as $tag)
                                    <span class="badge bg-light text-dark border me-1">{{ $tag->name }}</span>
                                @endforeach
                                @if($course->tags->count() > 3)
                                    <span class="badge bg-light text-dark border">+{{ $course->tags->count() - 3 }}</span>
                                @endif
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-muted small">
                                <i class="bi bi-person"></i> {{ $course->teacher->name }}
                            </span>
                            <span class="text-muted small">
                                <i class="bi bi-people"></i> {{ $course->enrollments_count }} студ.
                            </span>
                        </div>
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100 mt-3">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- All Courses -->
<div id="courses">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-book"></i> Все курсы</h2>
        @auth
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <a href="{{ route('courses.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Создать курс
                </a>
            @endif
        @endauth
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('courses.index') }}">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Поиск</label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Поиск по названию или описанию..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Категория</label>
                        <select name="category" class="form-select">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Тег</label>
                        <select name="tag" class="form-select">
                            <option value="">Все теги</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Цена</label>
                        <select name="price" class="form-select">
                            <option value="">Все цены</option>
                            <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Бесплатно</option>
                            <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Платно</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Преподаватель</label>
                        <select name="teacher" class="form-select">
                            <option value="">Все преподаватели</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Сортировка</label>
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Новые</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Популярные</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Цена ↑</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Цена ↓</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Применить
                        </button>
                    </div>
                </div>
                @if(request()->anyFilled(['search', 'category', 'tag', 'price', 'teacher', 'sort']))
                    <div class="mt-3">
                        <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Сбросить все фильтры
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if($courses->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Курсы скоро появятся!
        </div>
    @else
        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-md-4">
                    <div class="card course-card border-0 shadow-sm">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-book" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="mb-2">
                                @if($course->category)
                                    <span class="badge" style="background-color: {{ $course->category->color }}">
                                        <i class="bi {{ $course->category->icon }}"></i> {{ $course->category->name }}
                                    </span>
                                @endif
                            </div>
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($course->description, 100) }}</p>
                            @if($course->tags->count() > 0)
                                <div class="mb-2">
                                    @foreach($course->tags->take(3) as $tag)
                                        <span class="badge bg-light text-dark border me-1">{{ $tag->name }}</span>
                                    @endforeach
                                    @if($course->tags->count() > 3)
                                        <span class="badge bg-light text-dark border">+{{ $course->tags->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    <i class="bi bi-person"></i> {{ $course->teacher->name }}
                                </span>
                                <span class="badge bg-primary">
                                    {{ $course->price > 0 ? number_format($course->price, 0) . ' ₽' : 'Бесплатно' }}
                                </span>
                            </div>
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary w-100 mt-3">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $courses->links() }}
        </div>
    @endif
</div>

@endsection
