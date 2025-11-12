@extends('layouts.app')

@section('title', 'Настройки платформы - Админ')

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
            <a class="nav-link" href="{{ route('admin.statistics') }}"><i class="bi bi-graph-up"></i> Статистика</a>
            <a class="nav-link active" href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Настройки</a>
        </nav>
    </div>

    <div class="col-md-10">
        <h1 class="mb-4"><i class="bi bi-gear"></i> Настройки платформы</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- Общие настройки -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Общие настройки</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Название сайта</label>
                        <input type="text" 
                               name="general[site_name]" 
                               class="form-control" 
                               value="{{ $settings['general']['site_name'] ?? 'EduPoint' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание сайта</label>
                        <textarea name="general[site_description]" 
                                  class="form-control" 
                                  rows="3">{{ $settings['general']['site_description'] ?? 'Онлайн платформа для обучения' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email администратора</label>
                        <input type="email" 
                               name="general[admin_email]" 
                               class="form-control" 
                               value="{{ $settings['general']['admin_email'] ?? 'admin@edupoint.local' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email для контактов</label>
                        <input type="email" 
                               name="general[contact_email]" 
                               class="form-control" 
                               value="{{ $settings['general']['contact_email'] ?? 'info@edupoint.local' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" 
                               name="general[phone]" 
                               class="form-control" 
                               value="{{ $settings['general']['phone'] ?? '+7 (999) 123-45-67' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Адрес</label>
                        <input type="text" 
                               name="general[address]" 
                               class="form-control" 
                               value="{{ $settings['general']['address'] ?? 'г. Москва' }}">
                    </div>
                </div>
            </div>

            <!-- Настройки главной страницы -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-house"></i> Главная страница</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Заголовок Hero-секции</label>
                        <input type="text" 
                               name="homepage[hero_title]" 
                               class="form-control" 
                               value="{{ $settings['homepage']['hero_title'] ?? 'Учись с удовольствием' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Подзаголовок Hero-секции</label>
                        <textarea name="homepage[hero_subtitle]" 
                                  class="form-control" 
                                  rows="2">{{ $settings['homepage']['hero_subtitle'] ?? 'Получи новые знания и навыки вместе с EduPoint' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Текст кнопки Hero-секции</label>
                        <input type="text" 
                               name="homepage[hero_button]" 
                               class="form-control" 
                               value="{{ $settings['homepage']['hero_button'] ?? 'Начать обучение' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Показывать статистику</label>
                        <select name="homepage[show_stats]" class="form-select">
                            <option value="1" {{ ($settings['homepage']['show_stats'] ?? '1') == '1' ? 'selected' : '' }}>Да</option>
                            <option value="0" {{ ($settings['homepage']['show_stats'] ?? '1') == '0' ? 'selected' : '' }}>Нет</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Показывать популярные курсы</label>
                        <select name="homepage[show_popular_courses]" class="form-select">
                            <option value="1" {{ ($settings['homepage']['show_popular_courses'] ?? '1') == '1' ? 'selected' : '' }}>Да</option>
                            <option value="0" {{ ($settings['homepage']['show_popular_courses'] ?? '1') == '0' ? 'selected' : '' }}>Нет</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Количество популярных курсов</label>
                        <input type="number" 
                               name="homepage[popular_courses_count]" 
                               class="form-control" 
                               value="{{ $settings['homepage']['popular_courses_count'] ?? '6' }}" 
                               min="3" 
                               max="12">
                    </div>
                </div>
            </div>

            <!-- Настройки страницы "О нас" -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-square"></i> Страница "О нас"</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Заголовок "О нас"</label>
                        <input type="text" 
                               name="about[title]" 
                               class="form-control" 
                               value="{{ $settings['about']['title'] ?? 'О платформе EduPoint' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="about[description]" 
                                  class="form-control" 
                                  rows="5">{{ $settings['about']['description'] ?? 'EduPoint - современная образовательная платформа...' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Наша миссия</label>
                        <textarea name="about[mission]" 
                                  class="form-control" 
                                  rows="3">{{ $settings['about']['mission'] ?? 'Сделать качественное образование доступным для каждого' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Наше видение</label>
                        <textarea name="about[vision]" 
                                  class="form-control" 
                                  rows="3">{{ $settings['about']['vision'] ?? 'Стать лидирующей платформой онлайн-образования' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Настройки контактов -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-envelope"></i> Контакты и социальные сети</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ссылка на Facebook</label>
                        <input type="url" 
                               name="contact[facebook_url]" 
                               class="form-control" 
                               value="{{ $settings['contact']['facebook_url'] ?? '' }}" 
                               placeholder="https://facebook.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ссылка на Instagram</label>
                        <input type="url" 
                               name="contact[instagram_url]" 
                               class="form-control" 
                               value="{{ $settings['contact']['instagram_url'] ?? '' }}" 
                               placeholder="https://instagram.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ссылка на Twitter</label>
                        <input type="url" 
                               name="contact[twitter_url]" 
                               class="form-control" 
                               value="{{ $settings['contact']['twitter_url'] ?? '' }}" 
                               placeholder="https://twitter.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ссылка на LinkedIn</label>
                        <input type="url" 
                               name="contact[linkedin_url]" 
                               class="form-control" 
                               value="{{ $settings['contact']['linkedin_url'] ?? '' }}" 
                               placeholder="https://linkedin.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ссылка на YouTube</label>
                        <input type="url" 
                               name="contact[youtube_url]" 
                               class="form-control" 
                               value="{{ $settings['contact']['youtube_url'] ?? '' }}" 
                               placeholder="https://youtube.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telegram</label>
                        <input type="text" 
                               name="contact[telegram]" 
                               class="form-control" 
                               value="{{ $settings['contact']['telegram'] ?? '' }}" 
                               placeholder="@edupoint">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" 
                               name="contact[whatsapp]" 
                               class="form-control" 
                               value="{{ $settings['contact']['whatsapp'] ?? '' }}" 
                               placeholder="+7 999 123-45-67">
                    </div>
                </div>
            </div>

            <div class="text-end mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Сохранить все настройки
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
