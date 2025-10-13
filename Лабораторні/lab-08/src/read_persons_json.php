<?php
class Person {
    public $name;
    public $age;
}

$filename = 'persons.json';

// Зчитування JSON-файлу та десеріалізація об'єктів
$people = [];
if (file_exists($filename)) {
    $jsonContent = file_get_contents($filename);
    $people = json_decode($jsonContent);
}

// Виведення інформації про людей
foreach ($people as $person) {
    echo "Ім'я: {$person->name}, Вік: {$person->age}<br>";
}
?>
