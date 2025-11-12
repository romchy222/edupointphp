# Настройка Email Уведомлений

## 1. Конфигурация почты в .env

Добавьте следующие настройки в файл `.env`:

### Для Gmail (рекомендуется для тестирования):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ваш_email@gmail.com
MAIL_PASSWORD=ваш_пароль_приложения
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ваш_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Важно для Gmail**: Используйте "Пароль приложения" (App Password), а не обычный пароль:
1. Перейдите в Google Account → Security
2. Включите двухфакторную аутентификацию
3. Создайте "App Password" для "Mail"
4. Используйте сгенерированный 16-значный пароль

### Для Mailtrap (для разработки):

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=ваш_username
MAIL_PASSWORD=ваш_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@edupoint.test
MAIL_FROM_NAME="${APP_NAME}"
```

Получите учетные данные на [mailtrap.io](https://mailtrap.io)

### Для production (например, Mailgun, SendGrid):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=ваш_mailgun_username
MAIL_PASSWORD=ваш_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@вашдомен.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 2. Настройка очередей (Queue)

Уведомления используют очереди для асинхронной отправки. Настройте драйвер очередей:

### Для разработки (database):

```env
QUEUE_CONNECTION=database
```

Затем запустите worker:

```bash
php artisan queue:work
```

### Для production (Redis):

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Используйте supervisor или systemd для постоянной работы worker'а.

## 3. Тестирование

### Проверка конфигурации:

```bash
php artisan tinker
```

```php
Mail::raw('Test message', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

### Создание тестового урока:

1. Войдите как преподаватель или админ
2. Создайте курс и запишите на него студента
3. Добавьте новый урок в курс
4. Студент получит уведомление по email и в системе

## 4. Как работают уведомления

### NewLessonPublished

Отправляется студентам при создании нового урока в курсе, на который они записаны.

**Каналы:**
- Email (письмо с кнопкой перехода к уроку)
- Database (уведомление в навигационной панели)

**Когда срабатывает:** При вызове `LessonController@store`

### Просмотр уведомлений

- Иконка колокольчика в навигации показывает количество непрочитанных
- Клик по уведомлению отмечает его как прочитанное и перенаправляет к уроку
- Уведомления хранятся в таблице `notifications`

## 5. Добавление новых уведомлений

Пример создания уведомления о дедлайне курса:

```bash
php artisan make:notification CourseDeadlineReminder
```

В контроллере:

```php
$user->notify(new CourseDeadlineReminder($course));
```

## 6. Отладка

### Проверить failed jobs:

```bash
php artisan queue:failed
```

### Повторить failed jobs:

```bash
php artisan queue:retry all
```

### Логи:

```bash
tail -f storage/logs/laravel.log
```

## 7. Production Checklist

- [ ] Настроен надежный SMTP сервис (Mailgun, SendGrid, Amazon SES)
- [ ] Queue worker работает через supervisor/systemd
- [ ] Настроен мониторинг failed jobs
- [ ] Протестирована доставка на реальные email
- [ ] Настроены SPF/DKIM записи для домена
- [ ] Добавлен rate limiting для предотвращения спама

## 8. Дополнительные возможности

### Отключение email для отдельного пользователя:

Добавьте поле `email_notifications` в таблицу `users` и проверяйте в методе `via()`:

```php
public function via(object $notifiable): array
{
    $channels = ['database'];
    
    if ($notifiable->email_notifications) {
        $channels[] = 'mail';
    }
    
    return $channels;
}
```

### Группировка уведомлений:

Используйте Laravel's notification batching для отправки дайджеста вместо отдельных писем.

### Push-уведомления:

Добавьте канал 'broadcast' и используйте Laravel Echo + Pusher/Socket.io.
