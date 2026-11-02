<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>Каталог товарів</title>
</head>
<body>
<h1>Каталог товарів</h1>
<form method="get">
    <label>ID товару <input type="number" name="id" min="1" required></label>
    <button type="submit">Знайти</button>
</form>
<?php if ($error !== ''): ?>
    <p><?= escape($error) ?></p>
<?php else: ?>
    <ul>
    <?php foreach ($rows as $row): ?>
        <li><?= (int) $row->id ?>:
            <?= escape($row->getLabel()) ?></li>
    <?php endforeach; ?>
    </ul>
    <?php if ($rows === []): ?>
        <p>Каталог порожній.</p>
    <?php endif; ?>
    <?php if ($hasSearch): ?>
        <?php if ($found === false): ?>
            <p>Запис не знайдено.</p>
        <?php else: ?>
            <p><?= escape($found->getLabel()) ?></p>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
