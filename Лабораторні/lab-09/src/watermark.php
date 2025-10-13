<?php
// Відкриття основного зображення (ваш файл JPEG)
$source_image = imagecreatefromjpeg('input.jpg');



// Створення кольору для водяного знаку
$watermark_color = imagecolorallocate($source_image, 255, 255, 255); // Білий колір

// Величина шрифту водяного знаку
$font_size = 20;

// Текст водяного знаку
$watermark_text = 'Лабораторна робота 9';

// Визначення розмірів основного зображення
$source_width = imagesx($source_image);
$source_height = imagesy($source_image);

// Розмір тексту водяного знаку
$text_size = imagettfbbox($font_size, 0, 'C:\\Windows\\Fonts\\arial.ttf', $watermark_text);
$text_width = $text_size[2] - $text_size[0];
$text_height = $text_size[7] - $text_size[1];

// Розміщення водяного знаку по центру
$x = ($source_width - $text_width) / 2;
$y = ($source_height - $text_height) / 2;

// Додавання водяного знаку
imagettftext($source_image, $font_size, 0, (int) $x, (int) $y, $watermark_color, 'C:\\Windows\\Fonts\\arial.ttf', $watermark_text);

// Збереження нового зображення з водяним знаком
imagejpeg($source_image, 'output.jpg');


// Виведення зображення
header('Content-Type: image/png');
imagepng($source_image);

// Звільнення ресурсів
imagedestroy($source_image);
?>
