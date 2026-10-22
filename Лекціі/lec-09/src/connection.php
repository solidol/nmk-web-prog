<?php
declare(strict_types=1);

function connectDatabase(): PDO
{
    $config = [];
    foreach (['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'] as $key) {
        $value = getenv($key);
        if ($value === false || ($key !== 'DB_PASSWORD' && $value === '')) {
            throw new RuntimeException('Не налаштовано реквізити БД у середовищі PHP.');
        }
        $config[$key] = $value;
    }
    $port = filter_var($config['DB_PORT'], FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 65535]]);
    if ($port === false) {
        throw new RuntimeException('Некоректний порт БД.');
    }
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['DB_HOST'], $port, $config['DB_NAME']);
    return new PDO($dsn, $config['DB_USER'], $config['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
