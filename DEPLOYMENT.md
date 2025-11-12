# Инструкция по установке на Shared Hosting без SSH

Эта инструкция поможет вам развернуть Laravel приложение EduPoint на обычном shared-хостинге через FTP.

## Требования

- PHP 8.0 или выше
- MySQL 5.7 или выше
- Доступ к панели управления хостингом (cPanel, Plesk и т.д.)
- FTP-клиент (FileZilla, Total Commander и т.д.)

## Шаг 1: Подготовка локально

1. **Установите зависимости Composer локально:**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

2. **Настройте .env файл:**
   ```bash
   cp .env.example .env
   ```
   
   Откройте `.env` и настройте:
   - `APP_NAME` - название вашего приложения
   - `APP_URL` - URL вашего сайта
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` - данные MySQL базы

3. **Сгенерируйте ключ приложения:**
   ```bash
   php artisan key:generate
   ```

4. **Оптимизируйте кэш:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Шаг 2: Загрузка файлов на хостинг

### Вариант A: Структура с public_html

Если у вас есть папка `public_html`:

1. Загрузите все файлы проекта **КРОМЕ папки public** в корневую директорию (выше public_html)
2. Содержимое папки `public` загрузите в `public_html`
3. Отредактируйте `public_html/index.php`:

```php
// Измените путь к autoload.php
require __DIR__.'/../vendor/autoload.php';

// Измените путь к bootstrap
$app = require_once __DIR__.'/../bootstrap/app.php';
```

### Вариант B: Если нельзя загружать выше public_html

1. Загрузите весь проект в `public_html`
2. Создайте `.htaccess` в `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## Шаг 3: Настройка базы данных

1. Войдите в phpMyAdmin через панель управления хостингом
2. Создайте новую базу данных MySQL
3. Импортируйте структуру БД:
   - Выполните SQL из файла `database/setup.sql` (если создали)
   - ИЛИ выполните миграции через специальный скрипт (см. ниже)

### Запуск миграций без SSH

Создайте файл `migrate.php` в корне проекта:

```php
<?php
// migrate.php - для запуска миграций через браузер
// УДАЛИТЕ ЭТОТ ФАЙЛ ПОСЛЕ ИСПОЛЬЗОВАНИЯ!

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('migrate', ['--force' => true]);

echo $status === 0 ? "Миграции выполнены успешно!" : "Ошибка миграций";
```

Откройте в браузере: `https://your-domain.com/migrate.php`

**⚠️ ВАЖНО: Сразу удалите файл migrate.php после выполнения!**

## Шаг 4: Настройка прав доступа

Через FTP установите права (chmod):

- `storage/` и все подпапки: **777** (rwxrwxrwx)
- `bootstrap/cache/`: **777** (rwxrwxrwx)

## Шаг 5: Создание символической ссылки для storage

Создайте файл `storage-link.php` в public:

```php
<?php
// storage-link.php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('storage:link');

echo "Storage link created!";
// УДАЛИТЕ ЭТОТ ФАЙЛ после выполнения
```

Откройте: `https://your-domain.com/storage-link.php` и затем удалите файл.

## Шаг 6: Заполнение тестовыми данными

Создайте файл `seed.php`:

```php
<?php
// seed.php - для заполнения БД тестовыми данными
// УДАЛИТЕ ПОСЛЕ ИСПОЛЬЗОВАНИЯ!

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('db:seed');

echo "База данных заполнена!";
```

Откройте: `https://your-domain.com/seed.php` и удалите файл.

## Шаг 7: Финальная проверка

1. Откройте ваш сайт в браузере
2. Проверьте, что все работает корректно
3. Войдите с тестовыми учетными данными:
   - **Администратор**: admin@edupoint.com / password
   - **Преподаватель**: teacher@edupoint.com / password
   - **Студент**: student@edupoint.com / password

## Устранение проблем

### Ошибка 500

- Проверьте права доступа к папкам storage и bootstrap/cache
- Проверьте настройки .env файла
- Включите `APP_DEBUG=true` временно для просмотра ошибок

### Ошибки подключения к БД

- Убедитесь, что данные в .env правильные
- Некоторые хостинги используют не localhost, а 127.0.0.1 или IP-адрес

### CSS/JS не загружаются

- Проверьте, что создана символическая ссылка storage
- Проверьте APP_URL в .env

### PHP версия

Если хостинг использует PHP < 8.0:
1. В панели управления найдите настройки PHP
2. Измените версию на PHP 8.0 или выше
3. Часто это в разделе "Select PHP Version" в cPanel

## Рекомендации по безопасности

1. ✅ Измените все пароли по умолчанию
2. ✅ Установите `APP_DEBUG=false` в production
3. ✅ Убедитесь, что `.env` файл недоступен через браузер
4. ✅ Регулярно делайте резервные копии базы данных
5. ✅ Используйте SSL сертификат (Let's Encrypt бесплатный)

## Обновление приложения

1. Загрузите новые файлы через FTP (перезапишите старые)
2. Если были изменения в миграциях, выполните migrate.php
3. Очистите кэш через браузер (создайте clear-cache.php):

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('cache:clear');
$kernel->call('config:clear');
$kernel->call('view:clear');
echo "Кэш очищен!";
?>
```

## Поддержка

При возникновении проблем проверьте:
- Логи в `storage/logs/laravel.log`
- Настройки PHP в панели хостинга
- Права доступа к файлам

---

**Готово!** Ваша LMS-платформа EduPoint развернута на shared hosting.
