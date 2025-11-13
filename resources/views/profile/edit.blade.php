@extends('layouts.app')

@section('title', 'Редактировать профиль - EduPoint')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action">
                <i class="bi bi-person"></i> Профиль
            </a>
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                <i class="bi bi-gear"></i> Настройки
            </a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Информация профиля</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="avatar" class="form-label">Аватар</label>
                        @if(auth()->user()->avatar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                     alt="Avatar" 
                                     class="rounded-circle" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" 
                               name="avatar"
                               accept="image/*">
                        <small class="text-muted">Загрузите изображение (JPG, PNG, GIF). Максимум 2MB.</small>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', auth()->user()->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', auth()->user()->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">О себе</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="4">{{ old('bio', auth()->user()->bio) }}</textarea>
                        <small class="text-muted">Расскажите немного о себе (опционально)</small>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', auth()->user()->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="website" class="form-label">Веб-сайт</label>
                        <input type="url" 
                               class="form-control @error('website') is-invalid @enderror" 
                               id="website" 
                               name="website" 
                               value="{{ old('website', auth()->user()->website) }}"
                               placeholder="https://example.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Сохранить изменения
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Изменить пароль</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Текущий пароль</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Новый пароль</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-key"></i> Изменить пароль
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bell"></i> Настройки уведомлений</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.notifications') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="email_notifications" 
                                   name="email_notifications" 
                                   value="1"
                                   {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_notifications">
                                <strong>Включить email уведомления</strong>
                                <br><small class="text-muted">Получать уведомления на email</small>
                            </label>
                        </div>
                    </div>

                    <hr>

                    <p class="text-muted mb-3">Выберите типы уведомлений:</p>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="notify_new_lessons" 
                                   name="notify_new_lessons" 
                                   value="1"
                                   {{ auth()->user()->notify_new_lessons ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_new_lessons">
                                <i class="bi bi-play-circle text-primary"></i> Новые уроки
                                <br><small class="text-muted">Уведомление при добавлении нового урока в курс</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="notify_deadlines" 
                                   name="notify_deadlines" 
                                   value="1"
                                   {{ auth()->user()->notify_deadlines ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_deadlines">
                                <i class="bi bi-calendar-event text-warning"></i> Напоминания о дедлайнах
                                <br><small class="text-muted">Напоминания за 7, 3 и 1 день до окончания курса</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="notify_comments" 
                                   name="notify_comments" 
                                   value="1"
                                   {{ auth()->user()->notify_comments ? 'checked' : '' }}>
                            <label class="form-check-label" for="notify_comments">
                                <i class="bi bi-chat-dots text-success"></i> Ответы на комментарии
                                <br><small class="text-muted">Уведомление при ответе на ваш комментарий (будет реализовано)</small>
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Уведомления в приложении (колокольчик) будут приходить всегда, email уведомления зависят от ваших настроек.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Сохранить настройки
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
