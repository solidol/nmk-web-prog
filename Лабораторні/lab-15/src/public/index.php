<?php
require_once dirname(__DIR__) . '/app/core/Model.php';
$pdo = require dirname(__DIR__) . '/app/config/database.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Налаштування конкретної таблиці задаємо в коді, а не через URL.
$columns = ['title' => 'Назва', 'price' => 'Ціна, грн'];
$model = new Model($pdo, 'products', array_keys($columns));

header('Content-Type: text/html; charset=UTF-8');
if (isset($_GET['id'])) {
    $id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $item = $model->find($id);
    require dirname(__DIR__) . '/app/views/v_show.php';
} else {
    $items = $model->all();
    require dirname(__DIR__) . '/app/views/v_index.php';
}
