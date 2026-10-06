<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

runImage(function (): void {
    requireGd();
    // Клієнт обирає лише ключ; довільні шляхи й URL не приймаємо.
    $allowed = ['sample' => __DIR__ . '/images/sample.png'];
    $key = $_GET['image'] ?? 'sample';
    if (!is_string($key) || !isset($allowed[$key])) {
        throw new InvalidArgumentException('Невідоме зображення.');
    }
    $download = queryInt('download', 0, 0, 1) === 1;
    $path = $allowed[$key];
    if (!is_file($path) || !is_readable($path) || filesize($path) > 2 * 1024 * 1024) {
        throw new RuntimeException('Зображення недоступне або завелике.');
    }
    $info = getimagesize($path);
    if ($info === false || $info[2] !== IMAGETYPE_PNG || $info[0] < 400 || $info[1] < 200
        || $info[0] > 2000 || $info[1] > 2000 || $info[0] * $info[1] > 2000000) {
        throw new RuntimeException('Очікується PNG: від 400×200 до 2000×2000, не більше 2 млн пікселів.');
    }
    $image = imagecreatefrompng($path); // Розміри не замінюють перевірку декодування.
    if ($image === false) {
        throw new RuntimeException('Не вдалося декодувати PNG.');
    }
    imagealphablending($image, true);
    imagesavealpha($image, true);
    $width = imagesx($image);
    $height = imagesy($image);
    $panel = imagecolorallocatealpha($image, 15, 23, 42, 25);
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, $height - 55, $width - 1, $height - 1, $panel);
    date_default_timezone_set('Europe/Kyiv');
    // Шрифт — налаштування середовища, а не параметр HTTP-запиту.
    $font = getenv('LAB09_FONT') ?: __DIR__ . '/fonts/DejaVuSans.ttf';
    if (!is_file($font) && PHP_OS_FAMILY === 'Windows') {
        $font = 'C:/Windows/Fonts/arial.ttf';
    }
    if (is_readable($font) && function_exists('imagettftext')) {
        $label = 'ЛР 9 | Група DEMO | ' . date('d.m.Y');
        if (imagettftext($image, 14, 0, 15, $height - 20, $white, $font, $label) === false) {
            throw new RuntimeException('Не вдалося додати текст.');
        }
    } else {
        imagestring($image, 4, 15, $height - 35, 'Lab 09 | Group DEMO | ' . date('Y-m-d'), $white);
    }
    if (PHP_SAPI === 'cli') {
        // Серверне збереження лише за явного запуску з термінала.
        $bytes = pngBytes($image);
        $target = __DIR__ . '/watermark-output.png';
        if (file_put_contents($target, $bytes, LOCK_EX) !== strlen($bytes)) {
            throw new RuntimeException('Не вдалося зберегти результат.');
        }
        echo "Збережено watermark-output.png\n";
    } else {
        sendPng($image, $download);
    }
});
