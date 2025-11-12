@extends('layouts.app')

@section('title', 'Управление заявками - Админ')

@section('content')
<div class="row">
    <div class="col-md-2 sidebar" style="background: #f8f9fa; min-height: calc(100vh - 150px);">
        <h5 class="mb-3">Админ-панель</h5>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="nav-link" href="{{ route('admin.users') }}"><i class="bi bi-people"></i> Пользователи</a>
            <a class="nav-link" href="{{ route('admin.courses') }}"><i class="bi bi-book"></i> Курсы</a>
            <a class="nav-link active" href="{{ route('admin.messages') }}"><i class="bi bi-envelope"></i> Заявки</a>
            <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Отзывы</a>
            <a class="nav-link" href="{{ route('admin.statistics') }}"><i class="bi bi-graph-up"></i> Статистика</a>
            <a class="nav-link" href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Настройки</a>
        </nav>
    </div>

    <div class="col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-envelope"></i> Заявки из контактной формы</h1>
        </div>

        <!-- Фильтры -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Все статусы</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новые</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Решены</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Закрыты</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Таблица заявок -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Тема</th>
                                <th>Статус</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ $message->status === 'new' ? 'table-warning' : '' }}">
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->name }}</td>
                                    <td>{{ $message->email }}</td>
                                    <td>{{ Str::limit($message->subject, 50) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.messages.status', $message) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" 
                                                    class="form-select form-select-sm" 
                                                    onchange="this.form.submit()">
                                                <option value="new" {{ $message->status == 'new' ? 'selected' : '' }}>Новая</option>
                                                <option value="in_progress" {{ $message->status == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                                <option value="resolved" {{ $message->status == 'resolved' ? 'selected' : '' }}>Решена</option>
                                                <option value="closed" {{ $message->status == 'closed' ? 'selected' : '' }}>Закрыта</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Открыть
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
