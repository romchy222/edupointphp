@extends('layouts.app')

@section('title', 'Поиск - EduPoint')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <!-- Search Filters Sidebar -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Фильтры</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('search.index') }}" method="GET">
                        <input type="hidden" name="q" value="{{ $query }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Тип результатов</label>
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Все</option>
                                <option value="courses" {{ $type === 'courses' ? 'selected' : '' }}>Только курсы</option>
                                <option value="lessons" {{ $type === 'lessons' ? 'selected' : '' }}>Только уроки</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Категория</label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Все категории</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Тег</label>
                            <select name="tag" class="form-select" onchange="this.form.submit()">
                                <option value="">Все теги</option>
                                @foreach($tags as $t)
                                    <option value="{{ $t->id }}" {{ $tag == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Применить
                        </button>

                        @if($category || $tag || $type !== 'all')
                            <a href="{{ route('search.index', ['q' => $query]) }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="bi bi-x-circle"></i> Сбросить
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Popular Searches -->
            @if(count($popularSearches) > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-fire"></i> Популярные запросы</h6>
                    </div>
                    <div class="card-body">
                        @foreach($popularSearches as $popular)
                            <a href="{{ route('search.index', ['q' => $popular]) }}" class="badge bg-light text-dark border me-1 mb-2">
                                {{ $popular }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Search History -->
            @if(count($searchHistory) > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-clock-history"></i> История поиска</h6>
                    </div>
                    <div class="card-body">
                        @foreach($searchHistory as $history)
                            <a href="{{ route('search.index', ['q' => $history]) }}" class="badge bg-secondary me-1 mb-2">
                                {{ $history }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-9">
            <!-- Search Box -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('search.index') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control" placeholder="Поиск курсов, уроков..." value="{{ $query }}" autofocus>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Поиск
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(empty($query))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Введите запрос для поиска курсов и уроков
                </div>
            @else
                <!-- Search Results -->
                @php
                    $totalResults = $results['courses']->count() + $results['lessons']->count();
                @endphp

                <div class="mb-3">
                    <h4>Результаты поиска: "{{ $query }}"</h4>
                    <p class="text-muted">Найдено результатов: {{ $totalResults }}</p>
                </div>

                @if($totalResults === 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> По вашему запросу ничего не найдено. Попробуйте изменить запрос или фильтры.
                    </div>
                @else
                    <!-- Courses Results -->
                    @if($results['courses']->count() > 0)
                        <h5 class="mb-3"><i class="bi bi-book"></i> Курсы ({{ $results['courses']->count() }})</h5>
                        <div class="row g-3 mb-4">
                            @foreach($results['courses'] as $course)
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="mb-2">
                                                @if($course->category)
                                                    <span class="badge" style="background-color: {{ $course->category->color }}">
                                                        <i class="bi {{ $course->category->icon }}"></i> {{ $course->category->name }}
                                                    </span>
                                                @endif
                                                @if($course->duration_hours)
                                                    <span class="badge bg-info text-dark">
                                                        <i class="bi bi-clock"></i> {{ $course->getFormattedDuration() }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h5 class="card-title">{{ $course->title }}</h5>
                                            <p class="card-text text-muted small">{{ Str::limit($course->description, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $course->teacher->name }}
                                                </small>
                                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-primary">
                                                    Подробнее
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Lessons Results -->
                    @if($results['lessons']->count() > 0)
                        <h5 class="mb-3"><i class="bi bi-play-circle"></i> Уроки ({{ $results['lessons']->count() }})</h5>
                        <div class="list-group mb-4">
                            @foreach($results['lessons'] as $lesson)
                                <a href="{{ route('lessons.show', [$lesson->course_id, $lesson]) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $lesson->title }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $lesson->duration }} мин
                                        </small>
                                    </div>
                                    <p class="mb-1 text-muted small">{{ Str::limit($lesson->content, 150) }}</p>
                                    <small class="text-muted">
                                        Курс: <strong>{{ $lesson->course->title }}</strong>
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
