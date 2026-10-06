<?php
declare(strict_types=1);
require __DIR__ . '/common.php';
if (PHP_SAPI !== 'cli') {
    respond(405, ['error' => ['message' => 'Підготовка даних виконується лише з термінала.']]);
}
$store = ['next_id' => 3, 'tasks' => [
    ['id' => 1, 'title' => 'Перевірити GET', 'done' => false],
    ['id' => 2, 'title' => 'Підготувати тестові дані', 'done' => true],
]];
$path = dataPath();
$directory = dirname($path);
if (!is_dir($directory) && !mkdir($directory, 0700, true)) {
    fwrite(STDERR, "Не вдалося створити каталог сховища.\n");
    exit(1);
}
$json = json_encode($store, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
if (file_put_contents($path, $json, LOCK_EX) !== strlen($json)) {
    fwrite(STDERR, "Не вдалося записати початкові дані.\n");
    exit(1);
}
echo "Відновлено 2 початкові завдання. Сховище: $path\n";
