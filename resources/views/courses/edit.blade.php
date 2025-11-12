@extends('layouts.app')

@section('title', 'Редактировать курс - ' . $course->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Редактировать курс</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Название курса *</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $course->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание курса *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="5" 
                                  required>{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Цена (₽) *</label>
                        <input type="number" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" 
                               name="price" 
                               value="{{ old('price', $course->price) }}" 
                               min="0" 
                               step="0.01" 
                               required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Статус *</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>
                                Черновик
                            </option>
                            <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>
                                Опубликован
                            </option>
                            <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>
                                Архивирован
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($course->thumbnail)
                        <div class="mb-3">
                            <label class="form-label">Текущая обложка:</label><br>
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                 alt="{{ $course->title }}" 
                                 class="img-thumbnail" 
                                 style="max-width: 300px;">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Изменить обложку</label>
                        <input type="file" 
                               class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" 
                               name="thumbnail" 
                               accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Опасная зона</h5>
            </div>
            <div class="card-body">
                <p>Удаление курса приведет к удалению всех связанных уроков, тестов и записей студентов.</p>
                <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Вы уверены? Это действие нельзя отменить!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Удалить курс
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
