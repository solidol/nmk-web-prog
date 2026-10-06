<?php
declare(strict_types=1);

// Спільні функції прикладів; шляхи задає програма, а не користувач.
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function readText(string $path): string
{
    if (!is_file($path) || !is_readable($path)) {
        throw new RuntimeException('Файл відсутній або недоступний для читання.');
    }
    $text = file_get_contents($path);
    if ($text === false) {
        throw new RuntimeException('Не вдалося прочитати файл.');
    }
    if (trim($text) === '') {
        throw new RuntimeException('Файл порожній.');
    }
    return $text;
}

function parseXml(string $text): DOMDocument
{
    if (trim($text) === '') {
        throw new RuntimeException('XML порожній.');
    }
    $previous = libxml_use_internal_errors(true);
    libxml_clear_errors();
    try {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        // Не вмикаємо підстановку сутностей і завантаження DTD.
        if (!$dom->loadXML($text, LIBXML_NONET)) {
            throw new RuntimeException('Некоректний синтаксис XML.');
        }
        if ($dom->doctype !== null) {
            throw new RuntimeException('DTD у цьому навчальному форматі не дозволено.');
        }
        return $dom;
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
    }
}

function validatePeople(mixed $people): array
{
    if (!is_array($people) || !array_is_list($people)) {
        throw new RuntimeException('Очікується список записів.');
    }
    foreach ($people as $person) {
        if (!is_array($person) || count($person) !== 2
            || !isset($person['name'], $person['age'])
            || !is_string($person['name']) || trim($person['name']) === ''
            || !is_int($person['age']) || $person['age'] < 0 || $person['age'] > 120) {
            throw new RuntimeException('Запис має містити непорожнє ім’я та цілий вік 0–120.');
        }
    }
    return $people;
}

function peopleFromXml(DOMDocument $dom): array
{
    $root = $dom->documentElement;
    if ($root === null || $root->tagName !== 'people' || $root->attributes->length !== 0) {
        throw new RuntimeException('Очікується кореневий елемент people без атрибутів.');
    }
    $people = [];
    foreach ($root->childNodes as $node) {
        if ($node instanceof DOMText && trim($node->textContent) === '') {
            continue;
        }
        if (!$node instanceof DOMElement || $node->tagName !== 'person'
            || $node->attributes->length !== 0) {
            throw new RuntimeException('Очікується елемент person без атрибутів.');
        }
        $fields = [];
        foreach ($node->childNodes as $field) {
            if ($field instanceof DOMText && trim($field->textContent) === '') {
                continue;
            }
            if (!$field instanceof DOMElement
                || !in_array($field->tagName, ['name', 'age'], true)
                || array_key_exists($field->tagName, $fields)
                || $field->attributes->length !== 0) {
                throw new RuntimeException('Поля person мають бути name та age без повторів.');
            }
            foreach ($field->childNodes as $part) {
                if (!$part instanceof DOMText && !$part instanceof DOMCdataSection) {
                    throw new RuntimeException('Поле має містити лише текст.');
                }
            }
            $fields[$field->tagName] = $field->textContent;
        }
        $rawAge = $fields['age'] ?? '';
        $age = ctype_digit($rawAge)
            ? filter_var($rawAge, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 120]])
            : false;
        if ($age === false) {
            throw new RuntimeException('Вік має бути цілим числом 0–120 без початкових нулів.');
        }
        $fields['age'] = $age;
        $people[] = $fields;
    }
    return validatePeople($people);
}

function peopleToXml(array $people): DOMDocument
{
    $people = validatePeople($people);
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $root = $dom->createElement('people');
    $dom->appendChild($root);
    foreach ($people as $person) {
        $node = $dom->createElement('person');
        foreach ($person as $key => $value) {
            $field = $dom->createElement($key);
            $field->appendChild($dom->createTextNode((string) $value));
            $node->appendChild($field);
        }
        $root->appendChild($node);
    }
    return $dom;
}

function renderPeople(array $people, string $format, ?string $error): void
{
    if ($error !== null) {
        http_response_code(500);
    }
    header('Content-Type: text/html; charset=UTF-8');
    ?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Читання <?= e($format) ?></title>
    </head>
    <body>
        <h1>Навчальні записи: <?= e($format) ?></h1>
        <?php if ($error !== null): ?>
            <p><?= e($error) ?></p>
        <?php elseif ($people === []): ?>
            <p>Записів немає.</p>
        <?php else: ?>
            <table>
                <caption>Вигадані дані для перевірки</caption>
                <thead><tr><th scope="col">Ім’я</th><th scope="col">Вік</th></tr></thead>
                <tbody>
                <?php foreach ($people as $person): ?>
                    <tr><td><?= e($person['name']) ?></td><td><?= $person['age'] ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </body>
    </html>
    <?php
}
