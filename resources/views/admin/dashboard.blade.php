@extends('layouts.app')

@section('title', 'Админ-панель - Dashboard')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .sidebar {
        background: #f8f9fa;
        min-height: calc(100vh - 150px);
    }
    .sidebar .nav-link {
        color: #495057;
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background: #fff;
        color: #0d6efd;
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <h5 class="mb-3">Админ-панель</h5>
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link" href="{{ route('admin.users') }}">
                <i class="bi bi-people"></i> Пользователи
            </a>
            <a class="nav-link" href="{{ route('admin.courses') }}">
                <i class="bi bi-book"></i> Курсы
            </a>
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="bi bi-tag"></i> Категории
            </a>
            <a class="nav-link" href="{{ route('admin.tags.index') }}">
                <i class="bi bi-tags"></i> Теги
            </a>
            <a class="nav-link" href="{{ route('admin.messages') }}">
                <i class="bi bi-envelope"></i> Заявки
                @if($stats['new_messages'] > 0)
                    <span class="badge bg-danger">{{ $stats['new_messages'] }}</span>
                @endif
            </a>
            <a class="nav-link" href="{{ route('admin.reviews') }}">
                <i class="bi bi-star"></i> Отзывы
            </a>
            <a class="nav-link" href="{{ route('admin.statistics') }}">
                <i class="bi bi-graph-up"></i> Статистика
            </a>
            <a class="nav-link" href="{{ route('admin.settings') }}">
                <i class="bi bi-gear"></i> Настройки
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="col-md-10">
        <h1 class="mb-4">Dashboard</h1>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['total_users'] }}</h3>
                        <p class="text-muted mb-0">Пользователей</p>
                        <small class="text-success">+{{ $recentStats['new_users'] }} за 30 дней</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book text-success" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['total_courses'] }}</h3>
                        <p class="text-muted mb-0">Курсов</p>
                        <small class="text-success">+{{ $recentStats['new_courses'] }} за 30 дней</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-bookmark-check text-info" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['total_enrollments'] }}</h3>
                        <p class="text-muted mb-0">Записей</p>
                        <small class="text-success">+{{ $recentStats['new_enrollments'] }} за 30 дней</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-award text-warning" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['total_certificates'] }}</h3>
                        <p class="text-muted mb-0">Сертификатов</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Регистрации (7 дней)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="registrationChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-star-fill"></i> Популярные курсы</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($popularCourses as $course)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $course->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $course->teacher->name }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $course->enrollments_count }} студ.
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Последние пользователи</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Имя</th>
                                        <th>Email</th>
                                        <th>Роль</th>
                                        <th>Дата</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers->take(5) as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-envelope"></i> Новые заявки</h5>
                        @if($stats['new_messages'] > 0)
                            <span class="badge bg-danger">{{ $stats['new_messages'] }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($recentMessages->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentMessages as $message)
                                    <a href="{{ route('admin.messages.show', $message) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $message->name }}</strong>
                                            <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 text-muted small">{{ Str::limit($message->subject, 50) }}</p>
                                        <span class="badge bg-{{ $message->status === 'new' ? 'danger' : 'success' }}">
                                            {{ $message->status }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Нет новых заявок</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Registration Chart
    const ctx = document.getElementById('registrationChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($registrationChart['labels']) !!},
            datasets: [{
                label: 'Регистрации',
                data: {!! json_encode($registrationChart['data']) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
