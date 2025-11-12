<?php
/**
 * Скрипт для создания символической ссылки storage
 * 
 * Запустите один раз через браузер, затем УДАЛИТЕ файл!
 */

$secret_key = 'your-secret-key-here'; // ИЗМЕНИТЕ ЭТО!

if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Access denied');
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Создание символической ссылки для storage...</h1>";
echo "<pre>";

try {
    $kernel->call('storage:link');
    echo "\n✓ Символическая ссылка создана успешно!\n";
} catch (Exception $e) {
    echo "\n✗ Ошибка: " . $e->getMessage() . "\n";
    
    // Альтернативный метод
    $target = __DIR__ . '/storage/app/public';
    $link = __DIR__ . '/public/storage';
    
    if (file_exists($link)) {
        echo "\nСимволическая ссылка уже существует.\n";
    } else {
        if (symlink($target, $link)) {
            echo "\n✓ Символическая ссылка создана вручную!\n";
        } else {
            echo "\n✗ Не удалось создать символическую ссылку.\n";
            echo "Создайте её вручную через FTP или cPanel.\n";
        }
    }
}

echo "</pre>";
echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ УДАЛИТЕ ЭТОТ ФАЙЛ ПОСЛЕ ВЫПОЛНЕНИЯ!</p>";
?>
