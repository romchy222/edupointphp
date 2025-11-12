# Руководство по запуску LMS EduPoint

## Быстрый старт (локально)

### 1. Установка зависимостей
```bash
composer install
```

### 2. Настройка окружения
```bash
# Копирование файла конфигурации
copy .env.example .env

# Генерация ключа приложения
php artisan key:generate
```

### 3. Настройка базы данных
Отредактируйте `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edupoint
DB_USERNAME=root
DB_PASSWORD=
```

Создайте БД:
```sql
CREATE DATABASE edupoint CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Миграции и тестовые данные
```bash
# Выполнить миграции
php artisan migrate

# Заполнить тестовыми данными
php artisan db:seed
```

### 5. Создание символической ссылки для файлов
```bash
php artisan storage:link
```

### 6. Запуск сервера
```bash
php artisan serve
```

Откройте http://localhost:8000

## Тестовые учетные записи

После выполнения `php artisan db:seed` будут созданы:

### Администратор
- Email: admin@edupoint.ru
- Пароль: password
- Доступ: полный контроль над платформой

### Преподаватель
- Email: teacher@edupoint.ru
- Пароль: password
- Доступ: создание и управление курсами

### Студент
- Email: student@edupoint.ru
- Пароль: password
- Доступ: обучение на курсах

## Тестирование функционала

### Для Студента:
1. Войдите как student@edupoint.ru
2. Перейдите в "Каталог курсов"
3. Запишитесь на курс
4. Просмотрите уроки курса
5. Пройдите тест
6. Получите сертификат

### Для Преподавателя:
1. Войдите как teacher@edupoint.ru
2. Создайте новый курс
3. Добавьте уроки к курсу
4. Создайте тест с вопросами
5. Опубликуйте курс

### Для Администратора:
1. Войдите как admin@edupoint.ru
2. Откройте админ-панель
3. Управляйте пользователями (смена ролей)
4. Модерируйте курсы (смена статусов)

## Структура проекта

```
app/
├── Http/Controllers/          # Контроллеры
│   ├── AdminController.php    # Админ-панель
│   ├── AuthController.php     # Аутентификация
│   ├── CertificateController.php
│   ├── CourseController.php
│   ├── EnrollmentController.php
│   ├── LessonController.php
│   ├── ProfileController.php
│   └── TestController.php
├── Models/                    # Модели Eloquent
│   ├── Certificate.php
│   ├── Course.php
│   ├── Enrollment.php
│   ├── Lesson.php
│   ├── LessonProgress.php
│   ├── Test.php
│   ├── TestQuestion.php
│   ├── TestResult.php
│   └── User.php
├── Policies/
│   └── CoursePolicy.php       # Авторизация курсов
└── Services/
    └── CertificateService.php # Генерация сертификатов

database/
├── migrations/                # Схема БД (9 таблиц)
└── seeders/
    └── DatabaseSeeder.php     # Тестовые данные

resources/views/               # Blade-шаблоны
├── layouts/
│   └── app.blade.php         # Основной layout
├── auth/                     # Вход/регистрация
├── courses/                  # CRUD курсов
├── lessons/                  # CRUD уроков
├── tests/                    # Прохождение тестов
├── certificates/             # Просмотр сертификатов
├── profile/                  # Профиль пользователя
└── admin/                    # Админ-панель

routes/
└── web.php                   # Маршруты (~35 routes)
```

## Основные маршруты

### Публичные
- `/` - Главная (список курсов)
- `/login` - Вход
- `/register` - Регистрация
- `/courses` - Каталог курсов
- `/courses/{id}` - Страница курса

### Для авторизованных
- `/profile` - Профиль
- `/my-courses` - Мои курсы
- `/certificates` - Мои сертификаты

### Для преподавателей
- `/courses/create` - Создать курс
- `/courses/{id}/edit` - Редактировать курс
- `/courses/{id}/lessons/create` - Добавить урок
- `/courses/{id}/tests/create` - Создать тест

### Для администраторов
- `/admin` - Админ-панель
- `/admin/users` - Управление пользователями
- `/admin/courses` - Модерация курсов

## Развертывание на shared-хостинге

Следуйте инструкциям в **DEPLOYMENT.md**

Основные шаги:
1. Загрузите файлы по FTP
2. Импортируйте БД через phpMyAdmin
3. Настройте `.env`
4. Запустите `migrate.php` через браузер
5. Создайте символическую ссылку через `storage-link.php`

## Возможные проблемы

### Ошибка "Class 'DOMDocument' not found"
Включите расширение PHP: `extension=dom` в `php.ini`

### Ошибка загрузки файлов
Проверьте права доступа:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Не генерируются сертификаты PDF
Убедитесь, что установлена библиотека DomPDF:
```bash
composer require barryvdh/laravel-dompdf
```

## База данных

### Основные таблицы
- `users` - Пользователи с ролями
- `courses` - Курсы
- `lessons` - Уроки курсов
- `enrollments` - Записи на курсы
- `lesson_progress` - Прогресс по урокам
- `tests` - Тесты
- `test_questions` - Вопросы тестов
- `test_results` - Результаты тестирования
- `certificates` - Сертификаты

Подробнее см. **DATABASE.md**

## Дополнительная документация

- **README_PROJECT.md** - Полное описание проекта
- **DATABASE.md** - Структура БД, связи, примеры SQL
- **DEPLOYMENT.md** - Развертывание на хостинге
- **PROJECT_SUMMARY.md** - Краткое резюме проекта

## Техническая поддержка

При возникновении проблем проверьте:
1. Логи Laravel: `storage/logs/laravel.log`
2. Логи PHP на хостинге
3. Консоль браузера (F12) для JavaScript ошибок

## Лицензия

Этот проект создан для образовательных целей.
