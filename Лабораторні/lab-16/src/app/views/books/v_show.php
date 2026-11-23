<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Картка книги — ЛР 16</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<main class="container narrow">
    <header class="page-header">
        <p class="eyebrow">Лабораторна робота 16 · Екземпляр моделі</p>
        <nav><a href="index.php?model=users">Користувачі</a> · <a href="index.php?model=books">Книги</a></nav>
        <h1>Картка книги</h1>
        <a href="index.php?model=books">← До списку</a>
    </header>
    <?php if ($item !== null): ?>
        <article class="card profile">
            <span class="badge">ID <?= $item->id ?></span>
            <h2><?= e($item->title) ?></h2>
            <dl>
                <dt>Назва</dt><dd><?= e($item->title) ?></dd>
                <dt>Автор</dt><dd><?= e($item->author) ?></dd>
                <dt>Рік</dt><dd><?= e($item->year) ?></dd>
            </dl>
        </article>
    <?php else: ?>
        <p class="notice">Книгу не знайдено.</p>
    <?php endif; ?>
</main>
</body>
</html>
