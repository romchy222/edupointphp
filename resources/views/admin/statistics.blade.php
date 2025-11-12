@extends('layouts.app')

@section('title', 'Статистика - Админ')

@section('content')
<div class="row">
    <div class="col-md-2 sidebar" style="background: #f8f9fa; min-height: calc(100vh - 150px);">
        <h5 class="mb-3">Админ-панель</h5>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a class="nav-link" href="{{ route('admin.users') }}"><i class="bi bi-people"></i> Пользователи</a>
            <a class="nav-link" href="{{ route('admin.courses') }}"><i class="bi bi-book"></i> Курсы</a>
            <a class="nav-link" href="{{ route('admin.messages') }}"><i class="bi bi-envelope"></i> Заявки</a>
            <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Отзывы</a>
            <a class="nav-link active" href="{{ route('admin.statistics') }}"><i class="bi bi-graph-up"></i> Статистика</a>
            <a class="nav-link" href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Настройки</a>
        </nav>
    </div>

    <div class="col-md-10">
        <h1 class="mb-4"><i class="bi bi-graph-up"></i> Детальная статистика</h1>

        <!-- Общая статистика -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Пользователи</h5>
                        <h2>{{ $stats['users'] ?? 0 }}</h2>
                        <small>Всего зарегистрировано</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Курсы</h5>
                        <h2>{{ $stats['courses'] ?? 0 }}</h2>
                        <small>Всего курсов</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Записи</h5>
                        <h2>{{ $stats['enrollments'] ?? 0 }}</h2>
                        <small>Всего записей</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Сертификаты</h5>
                        <h2>{{ $stats['certificates'] ?? 0 }}</h2>
                        <small>Выдано сертификатов</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- График регистраций за 30 дней -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Регистрации за последние 30 дней</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationsChart" height="80"></canvas>
            </div>
        </div>

        <!-- График записей на курсы за 30 дней -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-book"></i> Записи на курсы за последние 30 дней</h5>
            </div>
            <div class="card-body">
                <canvas id="enrollmentsChart" height="80"></canvas>
            </div>
        </div>

        <!-- Топ-10 популярных курсов -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Топ-10 популярных курсов</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Курс</th>
                                <th>Преподаватель</th>
                                <th>Записи</th>
                                <th>Просмотры</th>
                                <th>Средний рейтинг</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCourses ?? [] as $index => $course)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('courses.show', $course) }}">
                                            {{ $course->title }}
                                        </a>
                                    </td>
                                    <td>{{ $course->teacher->name }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $course->enrollments_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $course->views ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($course->average_rating)
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= round($course->average_rating) ? '-fill' : '' }}"></i>
                                                @endfor
                                                <small>({{ number_format($course->average_rating, 1) }})</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Нет отзывов</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- График распределения по ролям -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Распределение пользователей по ролям</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="rolesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Активность по дням недели</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последняя активность -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-activity"></i> Последняя активность</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Пользователь</th>
                                <th>Действие</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivity ?? [] as $activity)
                                <tr>
                                    <td>{{ $activity['user'] }}</td>
                                    <td>{{ $activity['action'] }}</td>
                                    <td>{{ $activity['date'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // График регистраций за 30 дней
    const registrationsData = @json($registrationsData ?? []);
    new Chart(document.getElementById('registrationsChart'), {
        type: 'line',
        data: {
            labels: registrationsData.labels,
            datasets: [{
                label: 'Регистрации',
                data: registrationsData.data,
                borderColor: 'rgb(13, 110, 253)',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
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

    // График записей на курсы за 30 дней
    const enrollmentsData = @json($enrollmentsData ?? []);
    new Chart(document.getElementById('enrollmentsChart'), {
        type: 'bar',
        data: {
            labels: enrollmentsData.labels,
            datasets: [{
                label: 'Записи на курсы',
                data: enrollmentsData.data,
                backgroundColor: 'rgba(25, 135, 84, 0.7)',
                borderColor: 'rgb(25, 135, 84)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
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

    // График распределения по ролям
    const rolesData = @json($rolesData ?? []);
    new Chart(document.getElementById('rolesChart'), {
        type: 'doughnut',
        data: {
            labels: rolesData.labels,
            datasets: [{
                data: rolesData.data,
                backgroundColor: [
                    'rgba(13, 110, 253, 0.7)',
                    'rgba(25, 135, 84, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)'
                ],
                borderColor: [
                    'rgb(13, 110, 253)',
                    'rgb(25, 135, 84)',
                    'rgb(220, 53, 69)',
                    'rgb(255, 193, 7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // График активности по дням недели
    const activityData = @json($activityData ?? []);
    new Chart(document.getElementById('activityChart'), {
        type: 'radar',
        data: {
            labels: activityData.labels,
            datasets: [{
                label: 'Активность',
                data: activityData.data,
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderColor: 'rgb(220, 53, 69)',
                pointBackgroundColor: 'rgb(220, 53, 69)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(220, 53, 69)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endsection
