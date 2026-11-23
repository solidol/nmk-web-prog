<?php

require_once dirname(__DIR__) . '/app/core/Model.php';
require_once dirname(__DIR__) . '/app/models/User.php';
require_once dirname(__DIR__) . '/app/models/Book.php';

$pdo = require dirname(__DIR__) . '/app/config/database.php';

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

header('Content-Type: text/html; charset=UTF-8');

// Вибір моделі
$model = $_GET['model'] ?? 'users';

// ID запису (якщо передано)
$id = isset($_GET['id'])
    ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    : null;

// Отримання даних і підключення шаблонів
switch ($model) {

    case 'users':
        if ($id !== null) {
            $item = $id !== false && $id > 0
                ? User::find($pdo, $id)
                : null;

            require dirname(__DIR__) . '/app/views/users/v_show.php';
        } else {
            $items = User::all($pdo);

            require dirname(__DIR__) . '/app/views/users/v_index.php';
        }
        break;

    case 'books':
        if ($id !== null) {
            $item = $id !== false && $id > 0
                ? Book::find($pdo, $id)
                : null;

            require dirname(__DIR__) . '/app/views/books/v_show.php';
        } else {
            $items = Book::all($pdo);

            require dirname(__DIR__) . '/app/views/books/v_index.php';
        }
        break;

    default:
        http_response_code(404);
        exit('Невідома модель');
}
