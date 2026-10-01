<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $filename = 'saved_data.txt';

    // Відкриття файлу для запису
    $file = fopen($filename, 'w');

    if ($file) {
        // Запис даних у файл
        fwrite($file, $content);
        fclose($file);
        echo "Дані були успішно збережені у файлі '$filename'.";
    } else {
        echo "Не вдалося відкрити файл для запису.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Запис до файлу</title>
</head>
<body>
    <h1>Запис до файлу</h1>
    <form action="" method="post">
        <textarea name="content" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Зберегти">
    </form>
</body>
</html>
