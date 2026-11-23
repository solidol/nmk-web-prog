<?php
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/Group.php';
require_once __DIR__ . '/models/Student.php';
require __DIR__ . '/core/config.php';
$pdo = require __DIR__ . '/core/connection.php';

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

header('Content-Type: text/html; charset=UTF-8');
$groups = Group::all($pdo);
$students = Student::all($pdo);
?>
<!DOCTYPE html>
<html lang="uk">
<meta charset="utf-8">
<title>ЛР 17 — Зв’язки моделей</title>
<h1>Групи та студенти</h1>
<?php foreach ($groups as $group): ?>
    <h2><?= e($group->name) ?></h2>
    <?php $members = $group->students(); ?>
    <?php if ($members === []): ?>
        <p>У групі ще немає студентів.</p>
    <?php else: ?>
        <ul>
        <?php foreach ($members as $student): ?>
            <li><?= e($student->name) ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endforeach; ?>
<h2>Зворотний зв’язок: студент → група</h2>
<ul>
<?php foreach ($students as $student): ?>
    <?php $group = $student->group(); ?>
    <li><?= e($student->name) ?> — <?= e($group === null ? 'Без групи' : $group->name) ?></li>
<?php endforeach; ?>
</ul>
</html>
