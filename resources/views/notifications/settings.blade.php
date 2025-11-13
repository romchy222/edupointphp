@extends('layouts.app')

@section('title', 'Настройки уведомлений - EduPoint')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-gear"></i> Настройки уведомлений</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('notifications.update-settings') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <h5><i class="bi bi-envelope"></i> Email уведомления</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    <strong>Включить email уведомления</strong>
                                    <br><small class="text-muted">Получать уведомления на электронную почту</small>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3"><i class="bi bi-list-check"></i> Типы уведомлений</h5>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_new_lessons" name="notify_new_lessons" {{ $user->notify_new_lessons ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_new_lessons">
                                    <i class="bi bi-book text-primary"></i> <strong>Новые уроки</strong>
                                    <br><small class="text-muted">Уведомлять о публикации новых уроков в моих курсах</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_comments" name="notify_comments" {{ $user->notify_comments ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_comments">
                                    <i class="bi bi-chat-dots text-info"></i> <strong>Комментарии</strong>
                                    <br><small class="text-muted">Уведомлять о новых комментариях к урокам</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_progress" name="notify_progress" {{ $user->notify_progress ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_progress">
                                    <i class="bi bi-graph-up text-warning"></i> <strong>Напоминания о прогрессе</strong>
                                    <br><small class="text-muted">Напоминания о незавершенных курсах</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_certificates" name="notify_certificates" {{ $user->notify_certificates ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_certificates">
                                    <i class="bi bi-award text-success"></i> <strong>Сертификаты</strong>
                                    <br><small class="text-muted">Поздравления с получением сертификатов</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_deadlines" name="notify_deadlines" {{ $user->notify_deadlines ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_deadlines">
                                    <i class="bi bi-calendar-event text-danger"></i> <strong>Дедлайны</strong>
                                    <br><small class="text-muted">Напоминания о приближающихся дедлайнах курсов</small>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Сохранить настройки
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
