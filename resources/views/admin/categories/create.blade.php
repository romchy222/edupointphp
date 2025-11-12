@extends('layouts.app')

@section('title', 'Создать категорию - Админ-панель')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Создать категорию</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Название <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Slug будет сгенерирован автоматически</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label">Иконка (Bootstrap Icons) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" 
                                   name="icon" 
                                   value="{{ old('icon', 'bi-tag') }}" 
                                   required>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Например: bi-code-slash, bi-palette, bi-briefcase
                                <br><a href="https://icons.getbootstrap.com/" target="_blank">Список иконок</a>
                            </small>
                            <div class="mt-2">
                                <span>Предпросмотр: </span>
                                <i class="bi" id="icon-preview" style="font-size: 2rem;"></i>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Цвет <span class="text-danger">*</span></label>
                            <input type="color" 
                                   class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color', '#0d6efd') }}" 
                                   required>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Выберите цвет для badge категории</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order" class="form-label">Порядок сортировки</label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', 0) }}"
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Меньшее значение = выше в списке</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Статус</label>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Активна
                                </label>
                            </div>
                            <small class="text-muted">Неактивные категории не отображаются при создании курса</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Создать категорию
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview icon
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    
    function updateIconPreview() {
        iconPreview.className = 'bi ' + iconInput.value;
    }
    
    iconInput.addEventListener('input', updateIconPreview);
    updateIconPreview();
</script>
@endpush
@endsection
