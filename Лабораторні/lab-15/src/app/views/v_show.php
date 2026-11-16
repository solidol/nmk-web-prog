<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Картка запису — ЛР 15</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<main class="container narrow">
    <header class="page-header">
        <p class="eyebrow">Лабораторна робота 15 · Екземпляр Model</p>
        <h1>Картка запису</h1>
        <a href="index.php">← До списку</a>
    </header>
    <?php if ($item !== null): ?>
        <article class="card profile">
            <span class="badge">ID <?= $item->id ?></span>
            <dl>
                <?php foreach ($columns as $field => $label): ?>
                    <dt><?= e($label) ?></dt><dd><?= e((string) $item->$field) ?></dd>
                <?php endforeach; ?>
            </dl>
        </article>
    <?php else: ?>
        <p class="notice">Запис не знайдено.</p>
    <?php endif; ?>
</main>
</body>
</html>
