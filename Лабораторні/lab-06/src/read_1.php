<?php
require __DIR__ . '/common.php';
$error = '';
$lines = [];
$name = '';
try {
    $path = selectedPath();
    $name = basename($path);
    $handle = fopen($path, 'r');
    if ($handle === false) {
        throw new RuntimeException('Не вдалося відкрити файл.');
    }
    try {
        while (($line = fgets($handle)) !== false) {
            $lines[] = rtrim($line, "\r\n");
        }
        if (!feof($handle)) {
            throw new RuntimeException('Помилка під час читання.');
        }
    } finally {
        fclose($handle);
    }
} catch (RuntimeException $exception) {
    http_response_code(400);
    $error = $exception->getMessage();
}
?>
<!doctype html>
<html lang="uk">
<head><meta charset="UTF-8"><title>Порядкове читання</title></head>
<body>
<h1>Текст за рядками</h1>
<?php if ($error !== ''): ?>
    <p role="alert"><?= e($error) ?></p>
<?php else: ?>
    <h2><?= e($name) ?></h2>
    <?php foreach ($lines as $index => $line): ?>
        <p><?= $index + 1 ?>. <?= e($line) ?></p>
    <?php endforeach; ?>
<?php endif; ?>
<p><a href="write_1.php">Нова нотатка</a></p>
</body>
</html>
