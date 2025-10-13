<?php
$filename = "file.txt";
if (file_exists($filename)) {
    $content = file_get_contents($filename);
    echo nl2br(htmlspecialchars($content));
} else {
    echo "Файл не знайдено.";
}
?>
