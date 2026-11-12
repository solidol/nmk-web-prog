<?php
require_once dirname(__DIR__) . '/app/models/User.php';
$pdo = require dirname(__DIR__) . '/app/config/database.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

header('Content-Type: text/html; charset=UTF-8');

if (isset($_GET['id'])) {
    $id = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $user = User::find($pdo, $id);
    require dirname(__DIR__) . '/app/views/users/v_show.php';
} else {
    $users = User::all($pdo);
    require dirname(__DIR__) . '/app/views/users/v_index.php';
}
