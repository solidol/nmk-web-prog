<?php
class Person {
    public $name;
    public $age;
}

$filename = 'persons.xml';
$people = [];

$dom = new DOMDocument();
if ($dom->load($filename)) {
    $persons = $dom->getElementsByTagName('person');
    
    foreach ($persons as $personNode) {
        $person = new Person();
        $person->name = $personNode->getElementsByTagName('name')->item(0)->nodeValue;
        $person->age = $personNode->getElementsByTagName('age')->item(0)->nodeValue;
        $people[] = $person;
    }
} else {
    echo "Не вдалося прочитати XML файл.";
}

// Виведення масиву об'єктів
foreach ($people as $person) {
    echo "Ім'я: {$person->name}, Вік: {$person->age}<br>";
}
?>