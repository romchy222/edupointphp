@extends('layouts.app')

@section('title', 'Управление курсами - Админ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-book"></i> Управление курсами</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> К панели управления
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Преподаватель</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Студентов</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>
                                <a href="{{ route('courses.show', $course) }}">
                                    {{ Str::limit($course->title, 50) }}
                                </a>
                            </td>
                            <td>{{ $course->teacher->name }}</td>
                            <td>{{ $course->price > 0 ? number_format($course->price, 2) . ' ₽' : 'Бесплатно' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.courses.status', $course) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" 
                                            class="form-select form-select-sm" 
                                            onchange="this.form.submit()">
                                        <option value="draft" {{ $course->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ $course->status == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ $course->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $course->enrollments->count() }}</td>
                            <td>
                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
