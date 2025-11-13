@extends('layouts.app')

@section('title', 'Забыли пароль? - EduPoint')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">Восстановление пароля</h2>
                <p class="text-muted text-center mb-4">
                    Введите ваш email, и мы отправим вам ссылку для сброса пароля
                </p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email адрес</label>
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

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-envelope"></i> Отправить ссылку
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Вернуться к входу
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
