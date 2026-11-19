<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Картка користувача — ЛР 14</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<main class="container narrow">
    <header class="page-header">
        <p class="eyebrow">Лабораторна робота 14 · Екземпляр моделі</p>
        <h1>Картка користувача</h1>
        <a href="index.php">← До списку</a>
    </header>
    <?php if ($user !== null): ?>
        <article class="card profile">
            <span class="badge">ID <?= $user->id ?></span>
            <h2><?= e($user->username) ?></h2>
            <dl>
                <dt>Ім’я користувача</dt><dd><?= e($user->username) ?></dd>
                <dt>Електронна пошта</dt><dd><?= e($user->email) ?></dd>
            </dl>
        </article>
    <?php else: ?>
        <p class="notice">Користувача не знайдено.</p>
    <?php endif; ?>
</main>
</body>
</html>
