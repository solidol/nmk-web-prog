<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

$people = [];
$error = null;
try {
    $dom = parseXml(readText(__DIR__ . '/persons.xml'));
    $people = peopleFromXml($dom);
} catch (RuntimeException $exception) {
    $error = $exception->getMessage();
}
renderPeople($people, 'XML', $error);
