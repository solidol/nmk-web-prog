<?php
require_once __DIR__ . '/Product.php';

function escape($text)
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$rows = [];
$found = null;
$error = '';
$hasSearch = array_key_exists('id', $_GET);

try {
    $pdo = require __DIR__ . '/connection.php';
    $statement = $pdo->query('SELECT id, name, category FROM products ORDER BY id');
    while (($product = $statement->fetchObject(Product::class)) !== false) {
        $rows[] = $product;
    }

    if ($hasSearch) {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        if ($id === false || $id === null) {
            $error = 'Ідентифікатор має бути додатним цілим числом.';
        } else {
            $statement = $pdo->prepare(
                'SELECT id, name, category FROM products WHERE id = :id'
            );
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $found = $statement->fetchObject(Product::class);
        }
    }
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    $error = 'Каталог недоступний. Перевірте журнал PHP.';
    $rows = [];
}
require __DIR__ . '/catalog.view.php';
