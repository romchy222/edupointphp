@extends('layouts.app')

@section('title', 'Вход - EduPoint')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Вход в систему</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right"></i> Войти
                    </button>

                    <div class="text-center mb-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            <i class="bi bi-key"></i> Забыли пароль?
                        </a>
                    </div>

                    <div class="text-center">
                        <p class="mb-0">Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
                    </div>
                </form>

                <hr class="my-4">

                <div class="alert alert-info mb-0">
                    <strong>Тестовые аккаунты:</strong><br>
                    <small>
                        Студент: student@edupoint.com / password<br>
                        Преподаватель: teacher@edupoint.com / password<br>
                        Админ: admin@edupoint.com / password
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
