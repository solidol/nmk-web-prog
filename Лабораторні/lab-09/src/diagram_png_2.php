<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

runImage(function (): void {
    [$width, $height] = chartSize();
    $data = loadChartData();
    $layout = barLayout($data, $width, $height);
    $image = canvas($width, $height);
    $ink = imagecolorallocate($image, 30, 41, 59);
    $blue = imagecolorallocate($image, 37, 99, 235);
    imageline($image, $layout['left'], $layout['top'], $layout['left'], $layout['bottom'], $ink);
    imageline($image, $layout['left'], $layout['bottom'], $width - 25, $layout['bottom'], $ink);
    imagestring($image, 3, 10, $layout['top'], (string) $layout['max'], $ink);
    imagestring($image, 3, 30, $layout['bottom'] - 12, '0', $ink);
    foreach ($layout['bars'] as $bar) {
        if ($bar['value'] > 0) {
            imagefilledrectangle($image, $bar['x1'], $bar['y'], $bar['x2'], $bar['bottom'] - 1, $blue);
        }
        imagestring($image, 3, $bar['x1'], $bar['y'] - 18, (string) $bar['value'], $ink);
        imagestring($image, 3, $bar['x1'], $bar['bottom'] + 10, $bar['label'], $ink);
    }
    imagestring($image, 4, 55, 12, 'Club activities', $ink);
    sendPng($image);
});
