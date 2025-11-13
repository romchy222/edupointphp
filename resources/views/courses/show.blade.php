@extends('layouts.app')

@section('title', $course->title . ' - EduPoint')

@push('styles')
<style>
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating-input input[type="radio"] {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        margin: 0 2px;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input[type="radio"]:checked ~ label {
        color: #ffc107;
    }
</style>
@endpush

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
            
            <span class="badge bg-{{ $course->getLevelBadgeClass() }}">
                <i class="bi bi-bar-chart"></i> {{ $course->getLevelLabel() }}
            </span>

            @if($course->duration_hours)
                <span class="badge bg-info">
                    <i class="bi bi-clock"></i> {{ $course->getFormattedDuration() }}
                </span>
            @endif

            <span class="badge bg-primary">
                <i class="bi bi-book"></i> {{ $course->lessons->count() }} уроков
            </span>
            
            @if($course->tags->count() > 0)
                @foreach($course->tags->take(3) as $tag)
                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                @endforeach
                @if($course->tags->count() > 3)
                    <span class="badge bg-secondary">+{{ $course->tags->count() - 3 }}</span>
                @endif
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

        @if($course->what_you_will_learn)
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Чему вы научитесь</h5>
                </div>
                <div class="card-body">
                    <div style="white-space: pre-line;">{{ $course->what_you_will_learn }}</div>
                </div>
            </div>
        @endif

        @if($course->requirements)
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Требования</h5>
                </div>
                <div class="card-body">
                    <div style="white-space: pre-line;">{{ $course->requirements }}</div>
                </div>
            </div>
        @endif

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

        <!-- Отзывы -->
        <div class="mb-4">
            <h4>
                <i class="bi bi-star-fill text-warning"></i> Отзывы 
                @if($course->reviews->count() > 0)
                    <span class="badge bg-secondary">{{ $course->reviews->count() }}</span>
                @endif
            </h4>

            @if($course->reviews->avg('rating'))
                <div class="mb-3">
                    <div class="d-flex align-items-center">
                        <h2 class="me-3 mb-0">{{ number_format($course->reviews->avg('rating'), 1) }}</h2>
                        <div>
                            @php
                                $avgRating = $course->reviews->avg('rating');
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $avgRating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @elseif($i - 0.5 <= $avgRating)
                                    <i class="bi bi-star-half text-warning"></i>
                                @else
                                    <i class="bi bi-star text-muted"></i>
                                @endif
                            @endfor
                            <div class="text-muted small">на основе {{ $course->reviews->count() }} отзывов</div>
                        </div>
                    </div>
                </div>
            @endif

            @auth
                @if($isEnrolled && !$hasReviewed)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Оставить отзыв</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reviews.store', $course) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Оценка</label>
                                    <div class="rating-input">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                            <label for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Комментарий</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              id="comment" 
                                              name="comment" 
                                              rows="3" 
                                              required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Отправить отзыв
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth

            @if($course->reviews->count() > 0)
                @foreach($course->reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                            @auth
                                @if(auth()->id() === $review->user_id)
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger mt-2" 
                                                onclick="return confirm('Удалить отзыв?')">
                                            <i class="bi bi-trash"></i> Удалить
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Отзывов пока нет. Будьте первым!
                </div>
            @endif
        </div>
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
                        <!-- Progress Widget -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Ваш прогресс</span>
                                <span class="fw-bold text-primary">{{ $progress }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-gradient" 
                                     style="width: {{ $progress }}%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"
                                     role="progressbar">
                                </div>
                            </div>
                            <div class="text-muted small mt-1">
                                {{ $completedLessons ?? 0 }} из {{ $course->lessons->count() }} уроков завершено
                            </div>
                        </div>

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
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-bookmark-plus"></i> Записаться на курс
                            </button>
                        </form>
                    @endif

                    <!-- Кнопка избранного -->
                    <form action="{{ route('favorites.toggle', $course) }}" method="POST" class="mt-2">
                        @csrf
                        @if($course->isFavoritedBy(auth()->user()))
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-heart-fill"></i> В избранном
                            </button>
                        @else
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-heart"></i> Добавить в избранное
                            </button>
                        @endif
                    </form>

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

                <hr>
                <h6 class="mb-2"><i class="bi bi-share"></i> Поделиться</h6>
                <div class="d-flex gap-2">
                    <a href="https://vk.com/share.php?url={{ urlencode(route('courses.show', $course)) }}&title={{ urlencode($course->title) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary flex-grow-1"
                       title="Поделиться в VK">
                        <i class="bi bi-share"></i> VK
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode(route('courses.show', $course)) }}&text={{ urlencode($course->title) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-info flex-grow-1"
                       title="Поделиться в Telegram">
                        <i class="bi bi-telegram"></i> TG
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($course->title . ' ' . route('courses.show', $course)) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-success flex-grow-1"
                       title="Поделиться в WhatsApp">
                        <i class="bi bi-whatsapp"></i> WA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
