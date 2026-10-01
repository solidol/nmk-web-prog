<?php
require __DIR__ . '/common.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        checkCsrf();
        $file = $_FILES['myfile'] ?? null;
        if (!is_array($file) || !isset($file['error']) || !is_int($file['error'])) {
            throw new RuntimeException('Очікується один текстовий файл.');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Файл не отримано. Оберіть файл і перевірте ліміти PHP.');
        }
        if (!is_string($file['name'] ?? null) || !is_string($file['tmp_name'] ?? null)
            || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Некоректне завантаження.');
        }
        if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'txt') {
            throw new RuntimeException('Дозволено лише розширення .txt.');
        }
        $size = filesize($file['tmp_name']);
        if ($size === false || $size === 0 || $size > 102400) {
            throw new RuntimeException('Розмір файлу має бути від 1 байта до 100 КіБ.');
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if ($mime !== 'text/plain') {
            throw new RuntimeException('Вміст має визначатися як звичайний текст.');
        }
        $text = file_get_contents($file['tmp_name']);
        if ($text === false) {
            throw new RuntimeException('Не вдалося прочитати завантаження.');
        }
        validateText($text);
        $name = newFilename();
        if (!move_uploaded_file($file['tmp_name'], storageDir() . '/' . $name)) {
            throw new RuntimeException('Не вдалося зберегти файл.');
        }
        header('Location: read_file.php?file=' . rawurlencode($name), true, 303);
        exit;
    } catch (RuntimeException $exception) {
        $error = $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="uk">
<head><meta charset="UTF-8"><title>Імпорт тексту</title></head>
<body>
<h1>Імпорт допису для клубу</h1>
<p>Один непорожній файл .txt у UTF-8, до 100 КіБ.</p>
<?php if ($error !== ''): ?><p role="alert"><?= e($error) ?></p><?php endif; ?>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
    <label for="myfile">Текст допису</label>
    <input type="file" id="myfile" name="myfile" accept=".txt,text/plain" required>
    <button type="submit">Імпортувати</button>
</form>
<p><a href="write_1.php">Створити нотатку вручну</a></p>
</body>
</html>
