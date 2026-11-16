<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Універсальна модель — ЛР 15</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<main class="container">
    <header class="page-header">
        <p class="eyebrow">Лабораторна робота 15 · Універсальний Model</p>
        <h1>Каталог</h1>
        <p class="muted">Кожен рядок представлено екземпляром одного класу Model.</p>
    </header>
    <?php if ($items === []): ?>
        <section class="card"><h2>Список порожній</h2><p>У таблиці ще немає записів.</p></section>
    <?php else: ?>
        <section class="card table-scroll">
            <table>
                <caption>Кількість записів: <?= count($items) ?></caption>
                <thead><tr><th scope="col">ID</th>
                    <?php foreach ($columns as $label): ?><th scope="col"><?= e($label) ?></th><?php endforeach; ?>
                    <th scope="col">Перегляд</th></tr></thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <?php foreach ($columns as $field => $label): ?>
                            <td><?= e((string) $item->$field) ?></td>
                        <?php endforeach; ?>
                        <td><a href="index.php?id=<?= $item->id ?>">Відкрити запис <?= $item->id ?></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
