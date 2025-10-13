<?php
// Розмір та кольори
$width = 400;
$height = 400;
$total = 360; // Всього градусів

// Сектори та їхні відсотки
$sectors = [
    ['color' => 'red', 'percentage' => 40, 'label' => 'Sector 1'],
    ['color' => 'green', 'percentage' => 30, 'label' => 'Sector 2'],
    ['color' => 'blue', 'percentage' => 30, 'label' => 'Sector 3']
];

// Початок SVG-зображення
header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" ';
echo '"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
echo '<svg width="' . $width . '" height="' . $height . '" version="1.1" xmlns="http://www.w3.org/2000/svg">';

// Розрахунок кутів секторів
$start_angle = 0;
foreach ($sectors as $sector) {
    $angle = ($sector['percentage'] / 100) * $total;
    $end_angle = $start_angle + $angle;

    // Розрахунок координат для кругового сектора
    $start_x = $width / 2 + cos(deg2rad($start_angle)) * ($width / 2);
    $start_y = $height / 2 + sin(deg2rad($start_angle)) * ($height / 2);
    $end_x = $width / 2 + cos(deg2rad($end_angle)) * ($width / 2);
    $end_y = $height / 2 + sin(deg2rad($end_angle)) * ($height / 2);

    // Виведення кругового сектора
    echo '<path d="M ' . $width / 2 . ' ' . $height / 2 . ' L ' . $start_x . ' ' . $start_y . ' A ' . $width / 2 . ' ' . $height / 2 . ' 0 ' . ($angle > 180 ? 1 : 0) . ' 1 ' . $end_x . ' ' . $end_y . ' Z" fill="' . $sector['color'] . '" />';

    // Переміщення початку наступного сектора
    $start_angle = $end_angle;
}

// Закінчення SVG-зображення
echo '</svg>';
?>
