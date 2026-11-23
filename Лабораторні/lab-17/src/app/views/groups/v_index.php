<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/app.css">
<title>ЛР 17 — Зв’язки моделей</title>
</head>
<body>
<main class="container">
<h1>Групи та студенти</h1>
<?php foreach ($groups as $group): ?>
    <h2><?= e($group->name) ?></h2>
    <?php $members = $membersByGroup[$group->id]; ?>
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
    <?php $group = $groupByStudent[$student->id]; ?>
    <li><?= e($student->name) ?> — <?= e($group === null ? 'Без групи' : $group->name) ?></li>
<?php endforeach; ?>
</ul>
</main>
</body>
</html>
