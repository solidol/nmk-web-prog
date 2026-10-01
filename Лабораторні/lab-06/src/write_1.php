<?php
require __DIR__ . '/common.php';
$error = '';
$content = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        checkCsrf();
        $content = $_POST['content'] ?? '';
        if (!is_string($content)) {
            $content = '';
            throw new RuntimeException('Нотатка має бути текстовим полем.');
        }
        $text = validateText($content);
        $name = newFilename();
        $written = file_put_contents(storageDir() . '/' . $name, $text, LOCK_EX);
        if ($written === false || $written !== strlen($text)) {
            throw new RuntimeException('Не вдалося повністю записати нотатку.');
        }
        // Перехід на GET запобігає повторному запису при оновленні сторінки.
        header('Location: read_file.php?file=' . rawurlencode($name), true, 303);
        exit;
    } catch (RuntimeException $exception) {
        $error = $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="uk">
<head><meta charset="UTF-8"><title>Нотатка студентського клубу</title></head>
<body>
<h1>Нова нотатка</h1>
<p><a href="upload.php">Імпортувати текстовий файл</a></p>
<?php if ($error !== ''): ?><p role="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" action="write_1.php">
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
    <label for="content">Ідея для наступної зустрічі (до 100 КіБ)</label><br>
    <textarea id="content" name="content" rows="8" cols="60" required><?= e($content) ?></textarea><br>
    <button type="submit">Зберегти нотатку</button>
</form>
</body>
</html>
