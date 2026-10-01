<?php
require __DIR__ . '/common.php';
$error = '';
$text = '';
$name = '';
try {
    $path = selectedPath();
    $name = basename($path);
    $text = file_get_contents($path);
    if ($text === false) {
        throw new RuntimeException('Не вдалося прочитати файл.');
    }
} catch (RuntimeException $exception) {
    http_response_code(400);
    $error = $exception->getMessage();
}
?>
<!doctype html>
<html lang="uk">
<head><meta charset="UTF-8"><title>Перегляд тексту</title></head>
<body>
<h1>Збережений текст</h1>
<?php if ($error !== ''): ?>
    <p role="alert"><?= e($error) ?></p>
<?php else: ?>
    <h2><?= e($name) ?></h2>
    <pre><?= e($text) ?></pre>
    <p><a href="read_1.php?file=<?= e(rawurlencode($name)) ?>">Прочитати порядково</a></p>
<?php endif; ?>
<p><a href="write_1.php">Нова нотатка</a> · <a href="upload.php">Імпорт</a></p>
</body>
</html>
