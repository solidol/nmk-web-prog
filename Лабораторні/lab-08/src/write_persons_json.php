<?php
class Person {
    public $name;
    public $age;
}

$filename = 'people.json';

// Створення об'єктів "Person"
$person1 = new Person();
$person1->name = 'John';
$person1->age = 30;

$person2 = new Person();
$person2->name = 'Alice';
$person2->age = 25;

// Створення масиву об'єктів
$people = [$person1, $person2];

// Серіалізація та запис у JSON-файл
$jsonContent = json_encode($people, JSON_PRETTY_PRINT);
file_put_contents($filename, $jsonContent);

echo "Запис виконано успішно.";
?>
