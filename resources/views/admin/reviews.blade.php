@extends('layouts.app')

@section('title', 'Управление отзывами - Админ')

@section('content')
<div class="row">
    <div class="col-md-2 sidebar" style="background: #f8f9fa; min-height: calc(100vh - 150px);">
        <h5 class="mb-3">Админ-панель</h5>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="nav-link" href="{{ route('admin.users') }}"><i class="bi bi-people"></i> Пользователи</a>
            <a class="nav-link" href="{{ route('admin.courses') }}"><i class="bi bi-book"></i> Курсы</a>
            <a class="nav-link" href="{{ route('admin.messages') }}"><i class="bi bi-envelope"></i> Заявки</a>
            <a class="nav-link active" href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Отзывы</a>
            <a class="nav-link" href="{{ route('admin.statistics') }}"><i class="bi bi-graph-up"></i> Статистика</a>
            <a class="nav-link" href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Настройки</a>
        </nav>
    </div>

    <div class="col-md-10">
        <h1 class="mb-4"><i class="bi bi-star"></i> Управление отзывами</h1>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Курс</th>
                                <th>Студент</th>
                                <th>Рейтинг</th>
                                <th>Комментарий</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>
                                        <a href="{{ route('courses.show', $review->course) }}">
                                            {{ Str::limit($review->course->title, 30) }}
                                        </a>
                                    </td>
                                    <td>{{ $review->user->name }}</td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>{{ $review->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Удалить отзыв?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
