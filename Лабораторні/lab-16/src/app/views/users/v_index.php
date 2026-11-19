<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Користувачі — ЛР 14</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<main class="container">
    <header class="page-header">
        <p class="eyebrow">Лабораторна робота 14 · Модель і представлення</p>
        <h1>Користувачі</h1>
        <p class="muted">Список екземплярів моделі User.</p>
    </header>
    <?php if ($users === []): ?>
        <section class="card"><h2>Список порожній</h2><p>У навчальній базі ще немає користувачів.</p></section>
    <?php else: ?>
        <section class="card table-scroll">
            <table>
                <caption>Кількість користувачів: <?= count($users) ?></caption>
                <thead><tr><th scope="col">ID</th><th scope="col">Ім’я користувача</th>
                    <th scope="col">Електронна пошта</th><th scope="col">Перегляд</th></tr></thead>
                <tbody>
                <?php foreach ($users as $item): ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= e($item->username) ?></td>
                        <td><?= e($item->email) ?></td>
                        <td><a href="index.php?id=<?= $item->id ?>" aria-label="<?= e('Переглянути: ' . $item->username) ?>">Відкрити</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
    <footer class="muted">Паролі та їхні хеші не передаються у представлення.</footer>
</main>
</body>
</html>
