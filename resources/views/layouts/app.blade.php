<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduPoint - Онлайн платформа курсов')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-book"></i> EduPoint
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('courses.index') }}">Курсы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">О нас</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('courses.my') }}">Мои курсы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('favorites.index') }}">
                                <i class="bi bi-heart"></i> Избранное
                            </a>
                        </li>
                        @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('courses.create') }}">Создать курс</a>
                            </li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Админ-панель</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @else
                        <!-- Уведомления -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
                                <li class="dropdown-header">Уведомления</li>
                                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                    <li>
                                        <a class="dropdown-item small" href="{{ $notification->data['url'] ?? '#' }}" 
                                           onclick="event.preventDefault(); markAsRead('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">
                                            <div class="fw-bold">{{ $notification->data['title'] ?? 'Уведомление' }}</div>
                                            <div class="text-muted">{{ $notification->data['message'] ?? '' }}</div>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                    </li>
                                    @if(!$loop->last)
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                @empty
                                    <li class="dropdown-item text-muted small">Нет новых уведомлений</li>
                                @endforelse
                                @if(auth()->user()->unreadNotifications->count() > 5)
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="dropdown-item small text-center text-muted">
                                        +{{ auth()->user()->unreadNotifications->count() - 5 }} еще
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                    <i class="bi bi-list"></i> Все уведомления
                                </a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Профиль</a></li>
                                <li><a class="dropdown-item" href="{{ route('certificates.index') }}">Сертификаты</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Выход</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-book"></i> EduPoint
                    </h5>
                    <p class="text-white-50">
                        Современная платформа для онлайн-обучения. Учитесь у лучших преподавателей, 
                        развивайтесь и достигайте своих целей.
                    </p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white-50 hover-text-white"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white-50 hover-text-white"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white-50 hover-text-white"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white-50 hover-text-white"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Навигация</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Главная</a></li>
                        <li class="mb-2"><a href="{{ route('courses.index') }}" class="text-white-50 text-decoration-none">Курсы</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">О нас</a></li>
                        <li class="mb-2"><a href="{{ route('leaderboard.index') }}" class="text-white-50 text-decoration-none">Рейтинги</a></li>
                        @auth
                            <li class="mb-2"><a href="{{ route('courses.my') }}" class="text-white-50 text-decoration-none">Мои курсы</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Категории</h5>
                    <ul class="list-unstyled">
                        @php
                            $footerCategories = \App\Models\Category::orderBy('name')->limit(5)->get();
                        @endphp
                        @foreach($footerCategories as $category)
                            <li class="mb-2">
                                <a href="{{ route('courses.index', ['category' => $category->id]) }}" 
                                   class="text-white-50 text-decoration-none">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5 class="mb-3">Контакты</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Москва, Россия</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> info@edupoint.ru</li>
                        <li class="mb-2"><i class="bi bi-phone me-2"></i> +7 (495) 123-45-67</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0">&copy; {{ date('Y') }} EduPoint. Все права защищены.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Политика конфиденциальности</a>
                    <a href="#" class="text-white-50 text-decoration-none">Условия использования</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-hide alerts after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Mark notification as read
        function markAsRead(notificationId, url) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                if (url !== '#') {
                    window.location.href = url;
                } else {
                    location.reload();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
