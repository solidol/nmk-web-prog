<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Книги — ЛР 16</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<main class="container">
    <header class="page-header">
        <p class="eyebrow">Лабораторна робота 16 · Модель і представлення</p>
        <nav><a href="index.php?model=users">Користувачі</a> · <a href="index.php?model=books">Книги</a></nav>
        <h1>Книги</h1>
        <p class="muted">Список екземплярів моделі User.</p>
    </header>
    <?php if ($items === []): ?>
        <section class="card"><h2>Список порожній</h2><p>У навчальній базі ще немає книг.</p></section>
    <?php else: ?>
        <section class="card table-scroll">
            <table>
                <caption>Кількість книг: <?= count($items) ?></caption>
                <thead><tr><th scope="col">ID</th><th scope="col">Назва</th>
                    <th scope="col">Автор</th><th scope="col">Перегляд</th></tr></thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= e($item->title) ?></td>
                        <td><?= e($item->author) ?></td>
                        <td><a href="index.php?model=books&amp;id=<?= $item->id ?>" aria-label="<?= e('Переглянути: ' . $item->title) ?>">Відкрити</a></td>
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
