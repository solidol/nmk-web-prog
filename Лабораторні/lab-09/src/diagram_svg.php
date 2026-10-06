<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

runImage(function (): void {
    [$width, $height] = chartSize();
    $layout = barLayout(loadChartData(), $width, $height);
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height
        . '" width="' . $width . '" height="' . $height . '" role="img" aria-labelledby="title desc">';
    $svg .= '<title id="title">Активності клубу</title><desc id="desc">Стовпчаста діаграма; точні значення підписано біля стовпців.</desc>';
    $svg .= '<rect width="100%" height="100%" fill="white"/>';
    $svg .= '<path d="M 55 45 V ' . $layout['bottom'] . ' H ' . ($width - 25) . '" fill="none" stroke="#1e293b"/>';
    $svg .= '<g font-family="sans-serif" font-size="13" fill="#1e293b">';
    $svg .= '<text x="10" y="55">' . $layout['max'] . '</text><text x="30" y="' . $layout['bottom'] . '">0</text>';
    foreach ($layout['bars'] as $bar) {
        $svg .= '<rect x="' . $bar['x1'] . '" y="' . $bar['y'] . '" width="' . ($bar['x2'] - $bar['x1'])
            . '" height="' . ($bar['bottom'] - $bar['y']) . '" fill="#2563eb"/>';
        $svg .= '<text x="' . $bar['x1'] . '" y="' . ($bar['y'] - 8) . '">' . $bar['value'] . '</text>';
        $svg .= '<text x="' . $bar['x1'] . '" y="' . ($bar['bottom'] + 22) . '">' . xmlText($bar['label']) . '</text>';
    }
    $svg .= '</g></svg>';
    header('Content-Type: image/svg+xml; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    echo $svg;
});
