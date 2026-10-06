<?php
declare(strict_types=1);

function xmlText(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function queryInt(string $name, int $default, int $min, int $max): int
{
    if (!array_key_exists($name, $_GET)) {
        return $default;
    }
    $raw = $_GET[$name];
    if (!is_string($raw) || !ctype_digit($raw)) {
        throw new InvalidArgumentException("Параметр $name має бути цілим числом.");
    }
    $value = filter_var($raw, FILTER_VALIDATE_INT,
        ['options' => ['min_range' => $min, 'max_range' => $max]]);
    if ($value === false) {
        throw new InvalidArgumentException("Параметр $name має бути в межах {$min}–{$max}.");
    }
    return $value;
}

function chartSize(): array
{
    // Межі обмежують витрати пам’яті та залишають місце для підписів.
    return [queryInt('width', 640, 400, 1200), queryInt('height', 400, 300, 800)];
}

function loadChartData(): array
{
    $text = file_get_contents(__DIR__ . '/chart-data.json');
    if ($text === false) {
        throw new RuntimeException('Не вдалося прочитати дані діаграми.');
    }
    $data = json_decode($text, true, 32, JSON_THROW_ON_ERROR);
    if (!str_starts_with(ltrim($text), '[') || !is_array($data)
        || !array_is_list($data) || count($data) < 1 || count($data) > 10) {
        throw new RuntimeException('Очікується список із 1–10 категорій.');
    }
    foreach ($data as $row) {
        if (!is_array($row) || !isset($row['label'], $row['value'])
            || !is_string($row['label']) || !preg_match('/^[A-Z][A-Z0-9 ]{0,9}$/D', $row['label'])
            || !is_int($row['value']) || $row['value'] < 0 || $row['value'] > 1000000) {
            throw new RuntimeException('Категорія має містити коротку латинську мітку та ціле невід’ємне значення.');
        }
    }
    return $data;
}

function barLayout(array $data, int $width, int $height): array
{
    $left = 55;
    $top = 45;
    $bottom = $height - 55;
    $plotHeight = $bottom - $top;
    $slot = ($width - $left - 25) / count($data);
    $max = max(array_column($data, 'value'));
    $scale = max(1, $max); // Усі нулі не спричиняють ділення на нуль.
    $bars = [];
    foreach ($data as $i => $row) {
        $x1 = (int) round($left + $i * $slot + $slot * 0.15);
        $x2 = (int) round($left + ($i + 1) * $slot - $slot * 0.15);
        $y = $bottom - (int) round($row['value'] / $scale * $plotHeight);
        $bars[] = ['x1' => $x1, 'x2' => $x2, 'y' => $y, 'bottom' => $bottom] + $row;
    }
    return ['left' => $left, 'top' => $top, 'bottom' => $bottom, 'max' => $max, 'bars' => $bars];
}

function requireGd(): void
{
    if (!extension_loaded('gd') || !function_exists('imagepng')) {
        throw new RuntimeException('Для цього прикладу потрібне розширення GD із підтримкою PNG.');
    }
}

function canvas(int $width, int $height): GdImage
{
    requireGd();
    $image = imagecreatetruecolor($width, $height);
    if ($image === false) {
        throw new RuntimeException('Не вдалося створити зображення.');
    }
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $white);
    return $image;
}

function pngBytes(GdImage $image): string
{
    ob_start();
    try {
        if (!imagepng($image)) {
            throw new RuntimeException('Не вдалося закодувати PNG.');
        }
        $bytes = ob_get_contents();
    } finally {
        ob_end_clean();
    }
    if (!is_string($bytes) || !str_starts_with($bytes, "\x89PNG\r\n\x1a\n")) {
        throw new RuntimeException('Некоректний результат кодування PNG.');
    }
    return $bytes;
}

function sendPng(GdImage $image, bool $download = false): void
{
    $bytes = pngBytes($image); // Кодуємо до надсилання заголовків.
    header('Content-Type: image/png');
    header('X-Content-Type-Options: nosniff');
    if ($download) {
        header('Content-Disposition: attachment; filename="watermark.png"');
    }
    echo $bytes;
}

function runImage(callable $action): void
{
    $failed = false;
    // Перетворюємо попередження декодера/файлових функцій на контрольовану помилку.
    set_error_handler(static function (int $severity, string $message): never {
        throw new ErrorException($message, 0, $severity);
    });
    try {
        $action();
    } catch (InvalidArgumentException $exception) {
        $failed = true;
        http_response_code(400);
        header('Content-Type: text/plain; charset=UTF-8');
        echo $exception->getMessage();
    } catch (Throwable $exception) {
        $failed = true;
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
        error_log($exception->getMessage());
        echo 'Не вдалося сформувати зображення. Перевірте GD, дані, файли та журнал PHP.';
    } finally {
        restore_error_handler();
    }
    if ($failed && PHP_SAPI === 'cli') {
        exit(1);
    }
}
