<?php
// Готові допоміжні функції для локальних прикладів, PHP 8.1+.
session_start();
header('Content-Type: text/html; charset=UTF-8');
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function checkCsrf(): void
{
    $token = $_POST['csrf'] ?? null;
    if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        throw new RuntimeException('Форма застаріла або запит завеликий. Оновіть сторінку.');
    }
}

function storageDir(): string
{
    // Запускайте сервер із -t src: дані залишаються поза публічним каталогом.
    $directory = dirname(__DIR__) . '/storage';
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
        throw new RuntimeException('Не вдалося створити каталог даних.');
    }
    return $directory;
}

function validateText(string $text): string
{
    if (strlen($text) > 102400 || !mb_check_encoding($text, 'UTF-8')) {
        throw new RuntimeException('Потрібен текст UTF-8 розміром до 100 КіБ.');
    }
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    if (trim($text) === '' || str_contains($text, "\0")) {
        throw new RuntimeException('Текст порожній або містить нульові байти.');
    }
    return $text;
}

function newFilename(): string
{
    // Без двокрапок для Windows; випадковий суфікс розрізняє одночасні записи.
    return date('Y-m-d_H-i-s') . '_' . bin2hex(random_bytes(8)) . '.txt';
}

function selectedPath(): string
{
    $name = $_GET['file'] ?? null;
    if (!is_string($name) || !preg_match('/\A\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}_[a-f0-9]{16}\.txt\z/', $name)) {
        throw new RuntimeException('Некоректний ідентифікатор файлу.');
    }
    $path = storageDir() . '/' . $name;
    if (!is_file($path) || !is_readable($path) || is_link($path)) {
        throw new RuntimeException('Файл не знайдено або читання недоступне.');
    }
    return $path;
}
