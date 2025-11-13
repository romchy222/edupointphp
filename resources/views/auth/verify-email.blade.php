@extends('layouts.app')

@section('title', 'Подтвердите Email - EduPoint')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-5 text-center">
                <i class="bi bi-envelope-check text-primary" style="font-size: 5rem;"></i>
                <h2 class="mt-4 mb-3">Подтвердите ваш Email</h2>
                <p class="text-muted mb-4">
                    Спасибо за регистрацию! Мы отправили вам письмо с ссылкой для подтверждения. 
                    Пожалуйста, проверьте почту и перейдите по ссылке.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success">
                        Новая ссылка для подтверждения отправлена на ваш email!
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat"></i> Отправить письмо повторно
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none">
                            <i class="bi bi-box-arrow-right"></i> Выйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
