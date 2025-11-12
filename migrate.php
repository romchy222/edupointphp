<?php
/**
 * Скрипт для запуска миграций через браузер
 * 
 * ВАЖНО: После выполнения миграций УДАЛИТЕ этот файл!
 * Не оставляйте его на продакшн сервере!
 */

// Проверка безопасности - установите секретный ключ
$secret_key = 'your-secret-key-here'; // ИЗМЕНИТЕ ЭТО!

if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Access denied');
}

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Запуск миграций...</h1>";
echo "<pre>";

try {
    $status = $kernel->call('migrate', [
        '--force' => true,
    ]);

    if ($status === 0) {
        echo "\n✓ Миграции выполнены успешно!\n";
        
        // Опционально запускаем seeders
        if (isset($_GET['seed']) && $_GET['seed'] === 'true') {
            echo "\nЗаполнение базы тестовыми данными...\n";
            $kernel->call('db:seed', ['--force' => true]);
            echo "\n✓ Тестовые данные добавлены!\n";
        }
    } else {
        echo "\n✗ Ошибка при выполнении миграций\n";
    }
} catch (Exception $e) {
    echo "\n✗ Исключение: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ НЕ ЗАБУДЬТЕ УДАЛИТЬ ЭТОТ ФАЙЛ!</p>";
?>
