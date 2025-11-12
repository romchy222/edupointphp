@extends('layouts.app')

@section('title', 'Управление категориями - Админ-панель')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-tag"></i> Управление категориями</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Добавить категорию
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Иконка</th>
                                    <th>Название</th>
                                    <th>Slug</th>
                                    <th>Цвет</th>
                                    <th>Порядок</th>
                                    <th>Курсов</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>
                                            <i class="bi {{ $category->icon }} fs-4" style="color: {{ $category->color }}"></i>
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if($category->description)
                                                <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $category->color }}">
                                                {{ $category->color }}
                                            </span>
                                        </td>
                                        <td>{{ $category->order }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $category->courses_count }}</span>
                                        </td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge bg-success">Активна</span>
                                            @else
                                                <span class="badge bg-secondary">Неактивна</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Вы уверены? Категория будет удалена.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            @if($category->courses_count > 0) disabled title="Нельзя удалить - есть курсы" @endif>
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
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Категории не найдены. 
                        <a href="{{ route('admin.categories.create') }}">Добавить первую категорию</a>
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
