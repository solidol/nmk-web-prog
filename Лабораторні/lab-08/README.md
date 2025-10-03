[Перелік усіх робіт](README.md)

# Лабораторна робота №8. Робота з даними XML та JSON

## Мета роботи

Ознайомитися з основами роботи XML-розширення DOM та навчитися зберігати дані в XML файл

## Обладнання

Персональний комп'ютер. Пакет програм XAMPP. Текстовий редактор Sublime Text 3 або IDE NetBeans. Web-браузер Chrome, Firefox, Opera

## Теоретичні відомості

### Що таке XML?

XML (eXtensible Markup Language) — це розширювана мова розмітки, яка використовується для зберігання та передачі структурованих даних у вигляді тексту. XML-файли мають ієрархічну структуру з тегами.

**Приклад XML:**
```xml
<books>
  <book>
    <title>PHP для початківців</title>
    <author>Іван Іванов</author>
  </book>
  <book>
    <title>Вивчаємо XML</title>
    <author>Петро Петренко</author>
  </book>
</books>
```

### Що таке JSON?

JSON (JavaScript Object Notation) — це легкий текстовий формат обміну даними, який легко читається людиною і просто парситься машиною. Широко використовується для передачі даних між сервером і клієнтом.

**Приклад JSON:**
```json
[
  {"title": "PHP для початківців", "author": "Іван Іванов"},
  {"title": "Вивчаємо XML", "author": "Петро Петренко"}
]
```

### Робота з XML у PHP

Для роботи з XML у PHP часто використовують клас `DOMDocument`.

**Завантаження XML з файлу:**
```php
<?php
$dom = new DOMDocument();
$dom->load('books.xml');
$books = $dom->getElementsByTagName('book');
foreach ($books as $book) {
    $title = $book->getElementsByTagName('title')[0]->nodeValue;
    $author = $book->getElementsByTagName('author')[0]->nodeValue;
    echo "Назва: $title, Автор: $author<br>";
}
?>
```

**Створення XML та збереження у файл:**
```php
<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$books = $dom->createElement('books');
$book = $dom->createElement('book');
$title = $dom->createElement('title', 'PHP для початківців');
$author = $dom->createElement('author', 'Іван Іванов');
$book->appendChild($title);
$book->appendChild($author);
$books->appendChild($book);
$dom->appendChild($books);
$dom->save('books.xml');
?>
```

### Робота з JSON у PHP

**Читання JSON з файлу:**
```php
<?php
$data = file_get_contents('books.json');
$array = json_decode($data, true);
foreach ($array as $book) {
    echo "Назва: {$book['title']}, Автор: {$book['author']}<br>";
}
?>
```

**Запис масиву у JSON-файл:**
```php
<?php
$array = [
    ["title" => "PHP для початківців", "author" => "Іван Іванов"],
    ["title" => "Вивчаємо XML", "author" => "Петро Петренко"]
];
file_put_contents('books.json', json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
?>
```

## Хід роботи

1. Впевнитись, що пакет XAMPP встановлено та web-сервер Apache запущений
2. Перейти до каталогу `C:\xampp\htdocs\` та очистити його
3. Робота з DOM здійснюється в об'єктно-орієнтованому стилі, який ми почали вивчати на попередніх лабораторних роботах. Для початку роботи з ним потрібно створити об'єкт domDocument. На практиці це виглядає так:

```php
$dom_xml = new DomDocument;
```

4. При створенні нового об'єкта domDocument викликається конструктор `DOMDocument => __construct`, що має два необов'язкових аргументи, які вказують на версію документа і його кодування.

```php
__construct ([string version [, string encoding]])
```

5. Після створення нового об'єкта domDocument необхідно завантажити дані. Здійснюється це за допомогою методів `DOMDocument->load()` і `DOMDocuмент->loadXML()`. В першому випадку дані завантажуються з файлу, а в другому з рядка. На практиці це виглядає так:
   
```php
//Завантаження даних з файлу з явним зазначенням екземпляра класу
$dom_xml = new DomDocument;
$dom_xml->load('file.xml');

//Завантаження даних з файлу без явного зазначення екземпляра класу
$dom_xml=DomDocument::load('file.xml');

//Завантаження даних з рядка з явним зазначенням екземпляра класу
$dom_xml = new DomDocument;
$dom_xml->loadXML('<car><model>volvo</model></car>');

//Завантаження даних з рядка без явного зазначення екземпляра класу
$dom_xml = DomDocument::lоаdХМL('<car><model>volvo</model></car>');
```

6. Після того як дані завантажені здійснюється їх розбір. Здійснити це можна за допомогою методу `DomDocument->getElementsByTagName()`. Цей метод повертає колекцію об'єктів DOMNode.

Виконайте приклад та зверніть увагу на результат

```php
<?php
$dom_xml= new DomDocument();
$dom_xml->loadXML('<car><model>BMW 525i</model><model>Ford Focus 2</model></car>');
// Записуємо назву моделі у змінну
$mod=$dom_xml->getElementsByTagName("model");
// Відображення даних
foreach ($mod as $element)
{
    echo $element->nodeValue." ".$element->nodeName." ".'<br />';
}
?>
```

Вдоскональте скрипт. Виведіть зміст файлу на екран у структурованому вигляді (таблиця або HTML-перелік).

7. Збереження xml даних в файл здійснюється за допомогою методу `save()`

```php
<?php
$dom_xml= new DomDocument();
$dom_xml->loadXML('<car><model>BMW 525i</model><model>Ford Focus 2</model></car>');
$path="auto.xml";
echo $dom_xml->save($path);
?>
```

Впевніться, що в результаті роботи цього коду буде повернуто число 84 (кількість символів) і створений файл `auto.xml` з наступним вмістом:

```xml
<?xml version="1.0"?>
  <car>
    <model>BMW 525i</model>
    <model>Ford Focus 2</model>
  </car>
```

8. Розгляньте альтернативний до XML формат збереження даних JSON з наступним вмістом:

```json
[
  {"model":"BMW 525"},
  {"model":"Ford Focus 2"}
]
```

Порівняйте структуру збережених даних. Зробіть висновки

9.  Збережіть на диску файл із розширенням `.json` та розмістить там дані з прикладу.
10. За допомогою функцій `file_get_contents()` та `json_decode()` прочитайте зміст файла, виведіть його на екран у структурованому вигляді (таблиця або HTML-перелік). 
11. Додайте до цих даних ще один запис, та оновіть дані у файлі.
12. Виконайте індивідуальне завдання
  + Оберіть варіант виконання з наведених нижче
  + Створіть файл `book.xml` (або використайте інше ім'я відповідно до вашого завдання), який буде містити інформацію про книгу, зробіть розбір xml та виведіть цю інформацію
  + Створіть сторінку, яка буде виводити інформацію з XML у табличному вигляді
  + Створіть форму, поля якої відповідатимуть структурі `book.xml`. Створіть скрипт обробки цієї форми, який буде збурігати дані шляхом додавання у файл `book.xml`
  + Об'єднайте всі завдання в одну систему, опрацюйте її інтерфейс
13. Завдання підвищенної складності. За допомогою форм та посилань зробіть можливість редагування записів про книги.
14. За допомогою функцій `json_encode()`, `json_decode()` та `file_get_contents()` виконайте попереднє завдання, змінивши формат збереження даних з XML на JSON.
15. Збережіть обидва проекти.
16. Впевніться, що всі вихідні HTML-сторінки є валідними, використавши валідатор HTML-коду https://validator.w3.org/. За необхідності, виправити помилки та зауваження
17. Для проекту створіть папку "lab 08" в своєму репозиторії "web-progr" на Github. Завантажте кінцеві файли лабораторної роботи. 
18. Для кожного етапу роботи зробіть знімки екрану та додайте їх у звіт з описом кожного скіншота
19. Додайте програмний код завдання для самомтійного виконання
20. Дайте відповіді на контрольні запитання
21. Додайте в кінці звіту посилання на репозиторій та папку з лабораторною роботою
22. Збережіть звіт у форматі PDF
23. Додайте звіт до репозиторія

## Контрольні питання

1. Що таке XML?
2. Що таке JSON?
3. Який об'єкт необхідно створити для роботи з DOM?
4. Що повертають функції `DOMDocument->load()` і `DOMDocuмент->loadXML()`?
5. За допомогою якого методу можна зберегти xml-дані в файл?
6. Для чого використовуть функції `json_encode()` та `json_decode()`?

## Приклади

1. [Читання файлу XML](src/lab-08/read_persons_xml.php)
2. [Записування у файл XML](src/lab-08/write_persons_xml.php)
3. [Файл XML](src/lab-08/persons.xml)
4. [Читання файлу JSON](src/lab-08/read_persons_json.php)
2. [Записування у файл JSON](src/lab-08/write_persons_json.php)
3. [Файл JSON](src/lab-08/persons.json)

## Довідники та додаткові матеріали

1. [PHP Manual](https://www.php.net/manual/en/)
2. [Класс DOMDocument](https://www.php.net/manual/ru/class.domdocument.php)
3. [JavaScript Object Notation](https://www.php.net/manual/ru/book.json.php)
4. [XML](https://uk.wikipedia.org/wiki/XML)
5. [Разбор XML](https://www.php.net/manual/ru/book.xml.php)
6. [JSON](https://uk.wikipedia.org/wiki/JSON)
7. [Введение в JSON](https://www.json.org/json-ru.html)


## Альтернатвні теми за варіантами

Варіяант для виконання самостійних завдань

1. Каталог товарів продуктового магазину
2. Каталог книг у бібліотеці
3. Дошка оголошень
4. Фотогалерея
5. Каталог запчастин автомобілів
6. Індивідуальний електронний записник
7. Каталог рієлтора
8. Каталог бази будівельних матеріалів
9. Власний блог
10. Розклад маршрутів автовокзалу