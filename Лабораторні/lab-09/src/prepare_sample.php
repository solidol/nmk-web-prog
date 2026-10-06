<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    exit('Запустіть php prepare_sample.php у терміналі.');
}
runImage(function (): void {
    $image = canvas(640, 360);
    $blue = imagecolorallocate($image, 37, 99, 235);
    $green = imagecolorallocate($image, 22, 163, 74);
    $orange = imagecolorallocate($image, 234, 88, 12);
    imagefilledrectangle($image, 60, 60, 240, 240, $blue);
    imagefilledellipse($image, 370, 150, 180, 180, $green);
    imagefilledrectangle($image, 485, 60, 580, 240, $orange);
    $ink = imagecolorallocate($image, 30, 41, 59);
    imagestring($image, 5, 60, 270, 'PHP GD - sample image', $ink);
    $bytes = pngBytes($image);
    if (file_put_contents(__DIR__ . '/images/sample.png', $bytes) !== strlen($bytes)) {
        throw new RuntimeException('Не вдалося зберегти навчальне зображення.');
    }
    echo "Створено images/sample.png (640 x 360).\n";
});
