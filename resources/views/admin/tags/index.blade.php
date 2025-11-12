@extends('layouts.app')

@section('title', 'Управление тегами - Админ-панель')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-tags"></i> Управление тегами</h2>
            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Добавить тег
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($tags->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Slug</th>
                                    <th>Курсов</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                                        </td>
                                        <td><code>{{ $tag->slug }}</code></td>
                                        <td>
                                            <span class="badge bg-info">{{ $tag->courses_count }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.tags.edit', $tag) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.tags.destroy', $tag) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Вы уверены? Тег будет удален.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            @if($tag->courses_count > 0) disabled title="Нельзя удалить - используется в курсах" @endif>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Всего тегов: <strong>{{ $tags->count() }}</strong> | 
                            Используется: <strong>{{ $tags->where('courses_count', '>', 0)->count() }}</strong>
                        </p>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Теги не найдены. 
                        <a href="{{ route('admin.tags.create') }}">Добавить первый тег</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Назад в админ-панель
            </a>
        </div>
    </div>
</div>
@endsection
