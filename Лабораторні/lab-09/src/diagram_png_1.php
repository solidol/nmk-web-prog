<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

runImage(function (): void {
    [$width, $height] = chartSize();
    $image = canvas($width, $height);
    $values = [40, 30, 30];
    $colors = [[37, 99, 235], [22, 163, 74], [234, 88, 12]];
    $cx = (int) round($width / 2);
    $cy = (int) round($height / 2);
    $diameter = min($width, $height) - 100;
    $sum = array_sum($values);
    $start = 0;
    $running = 0;
    foreach ($values as $i => $value) {
        $running += $value;
        $end = (int) round($running / $sum * 360);
        $color = imagecolorallocate($image, ...$colors[$i]);
        imagefilledarc($image, $cx, $cy, $diameter, $diameter, $start, $end, $color, IMG_ARC_PIE);
        $start = $end;
    }
    $text = imagecolorallocate($image, 20, 20, 20);
    imagestring($image, 4, 15, 15, 'Shares: 40% / 30% / 30%', $text);
    sendPng($image);
});
