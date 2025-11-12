<?php
/**
 * Скрипт для очистки кэша Laravel
 * 
 * Используйте при обновлении приложения
 * После использования можно удалить
 */

$secret_key = 'your-secret-key-here'; // ИЗМЕНИТЕ ЭТО!

if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Access denied');
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Очистка кэша...</h1>";
echo "<pre>";

try {
    echo "Очистка кэша конфигурации...\n";
    $kernel->call('config:clear');
    
    echo "Очистка кэша маршрутов...\n";
    $kernel->call('route:clear');
    
    echo "Очистка кэша представлений...\n";
    $kernel->call('view:clear');
    
    echo "Очистка кэша приложения...\n";
    $kernel->call('cache:clear');
    
    echo "\n✓ Весь кэш очищен успешно!\n";
    
    // Оптимизация для production
    if (isset($_GET['optimize']) && $_GET['optimize'] === 'true') {
        echo "\nОптимизация для production...\n";
        $kernel->call('config:cache');
        $kernel->call('route:cache');
        $kernel->call('view:cache');
        echo "\n✓ Оптимизация завершена!\n";
    }
    
} catch (Exception $e) {
    echo "\n✗ Ошибка: " . $e->getMessage() . "\n";
}

echo "</pre>";
echo "<hr>";
echo "<p>Для оптимизации добавьте параметр: ?key={$secret_key}&optimize=true</p>";
?>
