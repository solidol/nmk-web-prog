<?php
// Розміри та кольори
$width = 400;
$height = 300;

// Створення нового зображення
$image = imagecreatetruecolor($width, $height);
$background_color = imagecolorallocate($image, 255, 255, 255);
$bar_color = imagecolorallocate($image, 0, 0, 255);
$border_color = imagecolorallocate($image, 0, 0, 0);

// Дані для стовпчатої діаграми (як висоти стовпців)
$data = [20, 50, 30, 80, 40];

// Кількість стовпців та їх відступи
$num_bars = count($data);
$bar_width = $width / $num_bars;
$bar_spacing = 10;

// Створення нового зображення
$image = imagecreatetruecolor($width, $height);

// Заповнення фону
imagefill($image, 0, 0, $background_color);

// Малювання стовпчатої діаграми
for ($i = 0; $i < $num_bars; $i++) {
    $x1 = $i * ($bar_width + $bar_spacing);
    $x2 = $x1 + $bar_width;
    $y1 = $height - $data[$i];
    $y2 = $height;

    imagefilledrectangle($image, $x1, $y1, $x2, $y2, $bar_color);
    imagerectangle($image, $x1, $y1, $x2, $y2, $border_color);
}

// Виведення зображення
header('Content-Type: image/png');
imagepng($image);

// Звільнення ресурсів
imagedestroy($image);
?>
