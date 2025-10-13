<?php
class Person {
    public $name;
    public $age;
}

$filename = 'persons1.xml';

// Функція для збереження масиву об'єктів у XML-файл
function savePeopleToXML($filename, $people) {
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->formatOutput = true;

    // Створення кореневого елемента
    $root = $dom->createElement('people');
    $dom->appendChild($root);

    // Додавання об'єктів "Person" у XML
    foreach ($people as $person) {
        $personNode = $dom->createElement('person');

        $nameNode = $dom->createElement('name', $person->name);
        $personNode->appendChild($nameNode);

        $ageNode = $dom->createElement('age', $person->age);
        $personNode->appendChild($ageNode);

        $root->appendChild($personNode);
    }

    // Збереження у файл
    $dom->save($filename);
}

// Функція для дозапису нового об'єкта "Person" у XML-файл
function appendPersonToXML($filename, $person) {
    $dom = new DOMDocument();
    $dom->load($filename);

    // Отримання кореневого елемента
    $root = $dom->documentElement;

    // Додавання нового об'єкта "Person" у XML
    $personNode = $dom->createElement('person');

    $nameNode = $dom->createElement('name', $person->name);
    $personNode->appendChild($nameNode);

    $ageNode = $dom->createElement('age', $person->age);
    $personNode->appendChild($ageNode);

    $root->appendChild($personNode);

    // Збереження змін у файл
    $dom->save($filename);
}

// Створення об'єктів "Person"
$person1 = new Person();
$person1->name = 'Dean';
$person1->age = 30;

$person2 = new Person();
$person2->name = 'Alice';
$person2->age = 25;

// Збереження масиву об'єктів у файл
$people = [$person1, $person2];
savePeopleToXML($filename, $people);

// Дозапис нового об'єкта "Person" у файл
$person3 = new Person();
$person3->name = 'Bob';
$person3->age = 35;
appendPersonToXML($filename, $person3);

echo "Запис та дозапис виконано успішно.";
?>
