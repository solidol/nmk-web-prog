<?php
$message = '';
if (isset($_FILES['myfile']) && $_FILES['myfile']['error'] === 0) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = basename($_FILES['myfile']['name']);
    $target = $uploadDir . $filename;
    $fileType = mime_content_type($_FILES['myfile']['tmp_name']);
    $fileSize = $_FILES['myfile']['size'];
    // Перевірка типу та розміру файлу
    if ($fileSize <= 1048576 && $fileType === 'text/plain') {
        if (move_uploaded_file($_FILES['myfile']['tmp_name'], $target)) {
            $message = "Файл успішно завантажено: <b>" . htmlspecialchars($filename) . "</b> (" . round($fileSize/1024, 2) . " КБ)";
            // Вивід вмісту файлу
            $content = file_get_contents($target);
            $message .= "<h3>Вміст файлу:</h3><pre style='background:#f4f4f4;padding:1em;border-radius:4px;'>" . htmlspecialchars($content) . "</pre>";
        } else {
            $message = "Помилка при завантаженні файлу.";
        }
    } else {
        $message = "Файл має бути текстовим (.txt) і не перевищувати 1 МБ.";
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завантаження файлу</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        form { background: #f9f9f9; padding: 1em; border-radius: 6px; max-width: 400px; }
        input[type="file"] { margin-bottom: 1em; }
        input[type="submit"] { padding: 0.5em 1em; }
        .msg { margin: 1em 0; padding: 1em; background: #e7f7e7; border-left: 4px solid #4caf50; }
        .error { background: #fbeaea; border-left: 4px solid #f44336; }
    </style>
</head>
<body>
    <h1>Завантаження текстового файлу</h1>
    <?php if ($message): ?>
        <div class="msg<?php echo (strpos($message, 'успішно') === false ? ' error' : ''); ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="myfile">Оберіть файл:</label><br>
        <input type="file" name="myfile" id="myfile" accept=".txt"><br>
        <input type="submit" value="Завантажити">
    </form>
</body>
</html>
