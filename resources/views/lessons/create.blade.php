@extends('layouts.app')

@section('title', 'Добавить урок - ' . $course->title)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('courses.my') }}">Мои курсы</a></li>
        <li class="breadcrumb-item"><a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a></li>
        <li class="breadcrumb-item active">Добавить урок</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Добавить урок</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('lessons.store', $course) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Название урока *</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Содержание урока</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="10">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="video_url" class="form-label">Ссылка на видео (YouTube, Vimeo и т.д.)</label>
                        <input type="url" 
                               class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" 
                               name="video_url" 
                               value="{{ old('video_url') }}" 
                               placeholder="https://www.youtube.com/watch?v=...">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">Прикрепленный файл (PDF, документы и т.д.)</label>
                        <input type="file" 
                               class="form-control @error('file') is-invalid @enderror" 
                               id="file" 
                               name="file">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Максимальный размер: 10 МБ</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_free" 
                               name="is_free" 
                               value="1" 
                               {{ old('is_free') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_free">
                            Бесплатный урок (доступен без записи на курс)
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Добавить урок
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
