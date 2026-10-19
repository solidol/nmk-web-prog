<?php
declare(strict_types=1);

// Реквізити задають у середовищі PHP, а не в публічному коді.
function connectDatabase(): mysqli
{
    $config = [];
    foreach (['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'] as $key) {
        $value = getenv($key);
        if ($value === false || ($key !== 'DB_PASSWORD' && $value === '')) {
            throw new RuntimeException('Не налаштовано параметри підключення до БД.');
        }
        $config[$key] = $value;
    }
    $port = filter_var($config['DB_PORT'], FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 65535]]);
    if ($port === false) {
        throw new RuntimeException('Некоректний порт БД.');
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli();
    try {
        $db->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        $db->real_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PASSWORD'], $config['DB_NAME'], $port);
        $db->set_charset('utf8mb4');
        return $db;
    } catch (Throwable $exception) {
        $db->close();
        throw $exception;
    }
}
