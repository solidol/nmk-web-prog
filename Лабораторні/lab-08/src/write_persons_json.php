<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

// Навчальний генератор замінює файл; запуск лише з термінала.
if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    exit('Запустіть: php write_persons_json.php');
}
try {
    $people = [
        ['name' => 'Олена', 'age' => 20],
        ['name' => 'Тарас', 'age' => 25],
    ];
    $people[] = ['name' => 'Клуб <Код> & друзі', 'age' => 0];
    $json = json_encode(validatePeople($people),
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    $content = $json . "\n";
    $bytes = file_put_contents(__DIR__ . '/persons.json', $content, LOCK_EX);
    if ($bytes === false || $bytes !== strlen($content)) {
        throw new RuntimeException('Не вдалося повністю записати JSON.');
    }
    echo "persons.json: записано $bytes байтів, 3 записи.\n";
} catch (JsonException $exception) {
    fwrite(STDERR, "Не вдалося закодувати JSON.\n");
    exit(1);
} catch (RuntimeException $exception) {
    fwrite(STDERR, $exception->getMessage() . "\n");
    exit(1);
}
