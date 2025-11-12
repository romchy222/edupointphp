@extends('layouts.app')

@section('title', $course->title . ' - EduPoint')

@section('content')
<div class="row">
    <div class="col-lg-8">
        @if($course->thumbnail)
            <img src="{{ asset('storage/' . $course->thumbnail) }}" class="img-fluid rounded mb-4" alt="{{ $course->title }}">
        @endif

        <h1>{{ $course->title }}</h1>
        
        <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
            @if($course->category)
                <span class="badge" style="background-color: {{ $course->category->color }}">
                    <i class="bi {{ $course->category->icon }}"></i> {{ $course->category->name }}
                </span>
            @endif
            
            @if($course->tags->count() > 0)
                @foreach($course->tags as $tag)
                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                @endforeach
            @endif
        </div>
        
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                <i class="bi bi-person"></i> <strong>Преподаватель:</strong> {{ $course->teacher->name }}
            </div>
            @if($isEnrolled)
                <div>
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Вы записаны</span>
                </div>
            @endif
        </div>

        @if($isEnrolled && $progress > 0)
            <div class="mb-4">
                <h6>Прогресс прохождения:</h6>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">{{ $progress }}%</div>
                </div>
            </div>
        @endif

        <div class="mb-4">
            <h4>Описание курса</h4>
            <p>{{ $course->description }}</p>
        </div>

        <div class="mb-4">
            <h4><i class="bi bi-list-ul"></i> Программа курса ({{ $course->lessons->count() }} уроков)</h4>
            
            @php
                $modules = $course->modules()->with(['lessons' => function($q) {
                    $q->orderBy('order');
                }])->orderBy('order')->get();
                $lessonsWithoutModule = $course->lessons()->whereNull('module_id')->orderBy('order')->get();
            @endphp

            @if($modules->count() > 0)
                @foreach($modules as $module)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-decoration-none text-dark w-100 text-start" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#module{{ $module->id }}">
                                    <i class="bi bi-folder"></i> {{ $module->title }}
                                    <small class="text-muted">({{ $module->lessons->count() }} уроков)</small>
                                    <i class="bi bi-chevron-down float-end"></i>
                                </button>
                            </h5>
                            @if($module->description)
                                <p class="mb-0 text-muted small px-3">{{ $module->description }}</p>
                            @endif
                        </div>
                        <div id="module{{ $module->id }}" class="collapse show">
                            <div class="list-group list-group-flush">
                                @foreach($module->lessons as $lesson)
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-play-circle"></i> {{ $lesson->title }}
                                            @if($lesson->is_free)
                                                <span class="badge bg-info text-dark ms-2">Бесплатный</span>
                                            @endif
                                        </div>
                                        @if($isEnrolled && $lesson->isCompletedBy(auth()->user()))
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($lessonsWithoutModule->count() > 0)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <button class="btn btn-link text-decoration-none text-dark w-100 text-start" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#noModule">
                                <i class="bi bi-file-earmark"></i> Уроки без модуля
                                <small class="text-muted">({{ $lessonsWithoutModule->count() }} уроков)</small>
                                <i class="bi bi-chevron-down float-end"></i>
                            </button>
                        </h5>
                    </div>
                    <div id="noModule" class="collapse show">
                        <div class="list-group list-group-flush">
                            @foreach($lessonsWithoutModule as $lesson)
                                <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-play-circle"></i> {{ $lesson->title }}
                                        @if($lesson->is_free)
                                            <span class="badge bg-info text-dark ms-2">Бесплатный</span>
                                        @endif
                                    </div>
                                    @if($isEnrolled && $lesson->isCompletedBy(auth()->user()))
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($modules->count() == 0 && $lessonsWithoutModule->count() == 0)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Уроки пока не добавлены
                </div>
            @endif
        </div>

        @if($course->tests->count() > 0)
            <div class="mb-4">
                <h4><i class="bi bi-clipboard-check"></i> Тесты</h4>
                <div class="list-group">
                    @foreach($course->tests as $test)
                        <a href="{{ route('tests.show', $test) }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-file-earmark-text"></i> {{ $test->title }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top: 20px;">
            <div class="card-body">
                <h4 class="card-title">{{ $course->price > 0 ? number_format($course->price, 2) . ' ₽' : 'Бесплатно' }}</h4>
                
                @guest
                    <p class="text-muted">Войдите, чтобы записаться на курс</p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Войти</a>
                @else
                    @if($isEnrolled)
                        @if($progress == 100)
                            <div class="alert alert-success">
                                <i class="bi bi-trophy"></i> Курс завершен!
                            </div>
                            <form action="{{ route('certificates.generate', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="bi bi-download"></i> Получить сертификат
                                </button>
                            </form>
                        @else
                            <a href="{{ route('lessons.show', [$course, $course->lessons->first()]) }}" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-play"></i> Продолжить обучение
                            </a>
                        @endif

                        @if(auth()->id() !== $course->teacher_id)
                            <form action="{{ route('courses.unenroll', $course) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Вы уверены?')">
                                    Отписаться
                                </button>
                            </form>
                        @endif
                    @else
                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-bookmark-plus"></i> Записаться на курс
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->isTeacher() && auth()->id() == $course->teacher_id)
                        <hr>
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                        <a href="{{ route('lessons.create', $course) }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-plus"></i> Добавить урок
                        </a>
                        <a href="{{ route('tests.create', $course) }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-plus"></i> Добавить тест
                        </a>
                    @endif
                @endguest

                <hr>
                <ul class="list-unstyled mb-0">
                    <li><i class="bi bi-book"></i> {{ $course->lessons->count() }} уроков</li>
                    <li><i class="bi bi-people"></i> {{ $course->enrollments->count() }} студентов</li>
                    @if($course->tests->count() > 0)
                        <li><i class="bi bi-clipboard-check"></i> {{ $course->tests->count() }} тестов</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
