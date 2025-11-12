@extends('layouts.app')

@section('title', 'Редактировать урок - ' . $lesson->title)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('courses.my') }}">Мои курсы</a></li>
        <li class="breadcrumb-item"><a href="{{ route('courses.show', $lesson->course) }}">{{ $lesson->course->title }}</a></li>
        <li class="breadcrumb-item active">Редактировать урок</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Редактировать урок</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('lessons.update', $lesson) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Название урока *</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $lesson->title) }}" 
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
                                  rows="10">{{ old('content', $lesson->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="video_url" class="form-label">Ссылка на видео</label>
                        <input type="url" 
                               class="form-control @error('video_url') is-invalid @enderror" 
                               id="video_url" 
                               name="video_url" 
                               value="{{ old('video_url', $lesson->video_url) }}">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($lesson->file_path)
                        <div class="mb-3">
                            <label class="form-label">Текущий файл:</label><br>
                            <a href="{{ asset('storage/' . $lesson->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i> Скачать текущий файл
                            </a>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="file" class="form-label">Изменить файл</label>
                        <input type="file" 
                               class="form-control @error('file') is-invalid @enderror" 
                               id="file" 
                               name="file">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_free" 
                               name="is_free" 
                               value="1" 
                               {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_free">
                            Бесплатный урок
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('lessons.show', [$lesson->course, $lesson]) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Удалить урок</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('lessons.destroy', $lesson) }}" onsubmit="return confirm('Вы уверены?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Удалить урок
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
