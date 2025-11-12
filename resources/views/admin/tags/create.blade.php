@extends('layouts.app')

@section('title', 'Создать тег - Админ-панель')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Создать тег</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Название тега <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Например: PHP, JavaScript, Design"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Slug будет сгенерирован автоматически</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Создать тег
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
