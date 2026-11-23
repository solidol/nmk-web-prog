<?php
// Значення середовища замінюють наведені навчальні налаштування.
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_NAME') ?: 'lab17';
$username = getenv('DB_USER') ?: 'lab17_user';
$password = (string) getenv('DB_PASSWORD');

$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

return new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
