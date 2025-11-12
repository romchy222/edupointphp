@extends('layouts.app')

@section('title', 'О нас - EduPoint')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Hero -->
        <div class="text-center mb-5">
            <h1 class="display-4 mb-3">О платформе EduPoint</h1>
            <p class="lead text-muted">
                Мы создаем возможности для онлайн-обучения
            </p>
        </div>

        <!-- About -->
        <div class="card mb-4">
            <div class="card-body p-5">
                <h2 class="mb-4"><i class="bi bi-lightbulb text-warning"></i> Наша миссия</h2>
                <p class="lead">
                    EduPoint - это современная платформа для онлайн-обучения, которая объединяет опытных преподавателей и мотивированных студентов.
                </p>
                <p>
                    Мы верим, что качественное образование должно быть доступно каждому. Наша платформа предоставляет удобные инструменты для создания, управления и прохождения онлайн-курсов в любое время и из любой точки мира.
                </p>
            </div>
        </div>

        <!-- Features -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-book-half text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Качественные курсы</h4>
                        <p class="text-muted">Только проверенные материалы от опытных преподавателей</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clock text-success" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Гибкий график</h4>
                        <p class="text-muted">Учитесь в удобное для вас время</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Сертификаты</h4>
                        <p class="text-muted">Получайте сертификаты об окончании</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Сообщество</h4>
                        <p class="text-muted">Общайтесь с другими студентами</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="card bg-primary text-white mb-4">
            <div class="card-body p-5">
                <h2 class="mb-4">Наши достижения</h2>
                <div class="row text-center">
                    <div class="col-md-4">
                        <h3 class="display-4">{{ \App\Models\Course::where('status', 'published')->count() }}+</h3>
                        <p>Курсов</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="display-4">{{ \App\Models\User::count() }}+</h3>
                        <p>Студентов</p>
                    </div>
                    <div class="col-md-4">
                        <h3 class="display-4">{{ \App\Models\Certificate::count() }}+</h3>
                        <p>Выданных сертификатов</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card">
            <div class="card-body p-5">
                <h2 class="mb-4"><i class="bi bi-envelope"></i> Свяжитесь с нами</h2>
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name', auth()->user()->name ?? '') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email', auth()->user()->email ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Тема</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" required value="{{ old('subject') }}">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Сообщение</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-send"></i> Отправить
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
