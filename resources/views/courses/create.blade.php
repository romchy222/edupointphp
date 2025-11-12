@extends('layouts.app')

@section('title', 'Создать курс - EduPoint')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Создать новый курс</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Название курса *</label>
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
                        <label for="description" class="form-label">Описание курса *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="5" 
                                  required>{{ old('description') }}</textarea>
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
                               value="{{ old('price', 0) }}" 
                               min="0" 
                               step="0.01" 
                               required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Укажите 0 для бесплатного курса</small>
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Обложка курса</label>
                        <input type="file" 
                               class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" 
                               name="thumbnail" 
                               accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Рекомендуемый размер: 800x600px, форматы: JPG, PNG</small>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Категория</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" 
                                name="category_id">
                            <option value="">Выберите категорию</option>
                            @foreach(\App\Models\Category::active()->ordered()->get() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Теги (можно выбрать несколько)</label>
                        <select class="form-select" 
                                name="tags[]" 
                                multiple 
                                size="5">
                            @foreach(\App\Models\Tag::orderBy('name')->get() as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Удерживайте Ctrl для выбора нескольких тегов</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.my') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Создать курс
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
