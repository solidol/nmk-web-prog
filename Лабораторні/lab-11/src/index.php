<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

$rows = [];
$code = '';
$error = null;
$db = null;
$status = 200;
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    header('Allow: GET');
    $status = 405;
    $error = 'Ця сторінка підтримує лише GET.';
} else {
    try {
        $rawCode = $_GET['code'] ?? '';
        if (!is_string($rawCode)) {
            throw new InvalidArgumentException('Код має бути рядком.');
        }
        $code = trim($rawCode);
        if ($code !== '' && !preg_match('/\A[A-Z0-9-]{3,20}\z/', $code)) {
            throw new InvalidArgumentException('Неприпустимий формат коду.');
        }
        $db = connectDatabase();
        $rows = findProducts($db, $code);
    } catch (InvalidArgumentException $exception) {
        $status = 400;
        $error = $exception->getMessage();
    } catch (Throwable $exception) {
        $status = 500;
        error_log('lab11 catalogue: code=' . $exception->getCode());
        $error = 'Каталог недоступний. Перевірте налаштування та журнал PHP.';
    } finally {
        if ($db !== null) {
            mysqli_close($db);
        }
    }
}
http_response_code($status);
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЛР 11 — каталог товарів</title>
    <style>
        body { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; font-family: sans-serif; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .5rem; border: 1px solid #94a3b8; text-align: left; }
        th { background: #e2e8f0; }
        .table-scroll { overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Каталог товарів</h1>
    <form action="index.php" method="get">
        <label for="code">Код товару (порожнє поле — список)</label>
        <input id="code" name="code" value="<?= e($code) ?>" maxlength="20">
        <button type="submit">Знайти</button>
        <a href="index.php">Усі товари</a>
    </form>
    <?php if ($error !== null): ?>
        <p role="alert"><?= e($error) ?></p>
    <?php elseif ($rows === []): ?>
        <p>Товарів не знайдено.</p>
    <?php else: ?>
        <div class="table-scroll">
            <table>
                <caption>Показано <?= count($rows) ?> записів; список обмежено 100 рядками</caption>
                <thead><tr><th scope="col">ID</th><th scope="col">Код</th><th scope="col">Назва</th>
                    <th scope="col">Опис</th><th scope="col">Ціна, грн</th><th scope="col">Створено</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr><td><?= (int) $row['id'] ?></td><td><?= e($row['product_code']) ?></td>
                        <td><?= e($row['product_name']) ?></td><td><?= e($row['product_desc']) ?></td>
                        <td><?= e($row['price']) ?></td><td><?= e($row['created_at']) ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>
