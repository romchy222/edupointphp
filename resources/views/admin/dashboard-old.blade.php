@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
<h1><i class="bi bi-speedometer2"></i> Админ-панель</h1>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h2>{{ $stats['total_users'] }}</h2>
                <p class="mb-0">Всего пользователей</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h2>{{ $stats['total_courses'] }}</h2>
                <p class="mb-0">Всего курсов</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-info text-white">
            <div class="card-body">
                <h2>{{ $stats['total_students'] }}</h2>
                <p class="mb-0">Студентов</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-warning text-white">
            <div class="card-body">
                <h2>{{ $stats['total_teachers'] }}</h2>
                <p class="mb-0">Преподавателей</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Последние пользователи</h5>
            </div>
            <div class="list-group list-group-flush">
                @foreach($recentUsers as $user)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $user->name }}</strong><br>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">Все пользователи</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Последние курсы</h5>
            </div>
            <div class="list-group list-group-flush">
                @foreach($recentCourses as $course)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $course->title }}</strong><br>
                                <small class="text-muted">{{ $course->teacher->name }}</small>
                            </div>
                            <span class="badge bg-{{ $course->status == 'published' ? 'success' : 'secondary' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.courses') }}" class="btn btn-sm btn-outline-primary">Все курсы</a>
            </div>
        </div>
    </div>
</div>
@endsection
