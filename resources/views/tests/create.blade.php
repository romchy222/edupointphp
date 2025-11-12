@extends('layouts.app')

@section('title', 'Создать тест - ' . $course->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Создать тест для курса "{{ $course->title }}"</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tests.store', $course) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Название теста *</label>
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
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="pass_score" class="form-label">Проходной балл (%) *</label>
                        <input type="number" 
                               class="form-control @error('pass_score') is-invalid @enderror" 
                               id="pass_score" 
                               name="pass_score" 
                               value="{{ old('pass_score', 70) }}" 
                               min="0" 
                               max="100" 
                               required>
                        @error('pass_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-secondary">
                            Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Создать тест
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
