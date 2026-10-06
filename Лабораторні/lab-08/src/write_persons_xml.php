<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

// Навчальний генератор замінює файл; запуск лише з термінала.
if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    exit('Запустіть: php write_persons_xml.php');
}
try {
    $people = [
        ['name' => 'Олена', 'age' => 20],
        ['name' => 'Тарас', 'age' => 25],
    ];
    $people[] = ['name' => 'Клуб <Код> & друзі', 'age' => 0];
    $dom = peopleToXml($people);
    $bytes = $dom->save(__DIR__ . '/persons.xml');
    if ($bytes === false) {
        throw new RuntimeException('Не вдалося зберегти XML.');
    }
    echo "persons.xml: записано $bytes байтів, 3 записи.\n";
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
