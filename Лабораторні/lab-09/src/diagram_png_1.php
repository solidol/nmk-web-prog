<?php
// Розмір та кольори
$width = 400;
$height = 400;

// Створення нового зображення
$image = imagecreatetruecolor($width, $height);

$background_color = imagecolorallocate($image, 255, 255, 255);
$border_color = imagecolorallocate($image, 0, 0, 0);

// Заповнення фону
imagefill($image, 0, 0, $background_color);

// Основний край кругової діаграми
$center_x = $width / 2;
$center_y = $height / 2;
$radius = 100;
$total = 360; // Всього градусів

// Сектор 1: 40%
$color1 = imagecolorallocate($image, 255, 0, 0);
$angle1 = (40 / 100) * $total;
imagefilledarc($image, $center_x, $center_y, $radius * 2, $radius * 2, 0, $angle1, $color1, IMG_ARC_PIE);

// Сектор 2: 30%
$color2 = imagecolorallocate($image, 0, 255, 0);
$angle2 = (30 / 100) * $total;
imagefilledarc($image, $center_x, $center_y, $radius * 2, $radius * 2, $angle1, $angle1 + $angle2, $color2, IMG_ARC_PIE);

// Сектор 3: 30%
$color3 = imagecolorallocate($image, 0, 0, 255);
$angle3 = (30 / 100) * $total;
imagefilledarc($image, $center_x, $center_y, $radius * 2, $radius * 2, $angle1 + $angle2, $angle1 + $angle2 + $angle3, $color3, IMG_ARC_PIE);


// Виведення зображення
header('Content-Type: image/png');
imagepng($image);

// Звільнення ресурсів
imagedestroy($image);
?>
