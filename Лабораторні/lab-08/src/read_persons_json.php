<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

$people = [];
$error = null;
try {
    $json = readText(__DIR__ . '/persons.json');
    // Спочатку перевіряємо, що верхній рівень — JSON-масив, а не об’єкт.
    $decoded = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
    if (!is_array($decoded)) {
        throw new RuntimeException('Кореневе значення JSON має бути масивом.');
    }
    $people = validatePeople(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
} catch (JsonException $exception) {
    $error = 'Некоректний JSON або кодування UTF-8.';
} catch (RuntimeException $exception) {
    $error = $exception->getMessage();
}
renderPeople($people, 'JSON', $error);
