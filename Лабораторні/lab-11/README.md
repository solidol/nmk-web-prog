[Перелік усіх робіт](README.md)

# Лабораторна робота №11. Робота з СКБД MySQL та оболонкою phpMyAdmin

## Мета роботи

Вивчити основи баз даних і навчитися їх створювати в СУБД MySQL

## Обладнання

Персональний комп'ютер. Пакет програм XAMPP. Текстовий редактор Sublime Text 3 або IDE NetBeans. Web-браузер Chrome, Firefox, Opera

## Теоретичні відомості

Бібліотека MySQLi (MySQL Improved Extension) є однією з розширень мови програмування PHP, яка призначена для взаємодії з системою керування базами даних MySQL. MySQLi є покращеною версією старішої бібліотеки MySQL, яка пропонує додаткові функції та забезпечує більше можливостей для роботи з MySQL-базами даних. Ось декілька ключових особливостей і функцій бібліотеки MySQLi:

- Підтримка підготовлених запитів: MySQLi дозволяє створювати та виконувати підготовлені запити, що дозволяє попередити SQL-ін'єкції та покращити безпеку додатків.
- Підтримка транзакцій: Ви можете використовувати MySQLi для реалізації транзакцій, що дозволяє вам групувати кілька запитів разом і забезпечувати атомарність операцій.
- Підтримка об'єктно-орієнтованого та процедурного стилю: MySQLi підтримує обидва стилі програмування, дозволяючи вибрати підхід, який вам більше подобається.
- Підтримка з'єднань з сервером баз даних: MySQLi дозволяє вам керувати з'єднаннями з сервером баз даних, включаючи встановлення, закриття та поновлення з'єднань.
- Обробка результатів запитів: Ви можете легко отримувати та обробляти результати запитів до бази даних, включаючи вибірку, вставку, оновлення та видалення даних.
- Підтримка роботи з рядками з'єднань (connection pooling): MySQLi може оптимізувати роботу з з'єднаннями до бази даних, що зменшує витрати ресурсів сервера та підвищує продуктивність.
- Підтримка багатофункціональних запитів (multi-query): MySQLi дозволяє виконувати кілька запитів у одному виклику, що дозволяє вам виконувати багато операцій разом.

За допомогою бібліотеки MySQLi ви можете легко створювати потужні та безпечні додатки, які взаємодіють з базами даних MySQL. MySQLi є стандартним вибором для багатьох розробників PHP, які працюють з MySQL, і надає ряд зручних функцій для спрощення роботи з базами даних.

## Хід роботи

1.  Запустіть web-сервер Apache та СУБД MySQL у вікні прикладки XAMPP Control Panel.
2.  Відкрийте браузер і в адресному рядку введіть наступну адресу: `http://localhost/phpmyadmin/`
3.  Створіть нову БД з ім'ям Products.
4.  Зробити створену базу даних активною, вибравши її ім'я в списку баз даних.
5.  Перейдіть на вкладку SQL та виконайте наступний скрипт.

```SQL
    CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(60) NOT NULL,
  `product_name` varchar(60) NOT NULL,
  `product_desc` tinytext NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code` (`product_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=33;

INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_desc`, `price`)
VALUES
  (1, 'PD1001', 'Android Phone FX1', 'Di sertakan secara rambang yang lansung tidak munasabah. Jika anda ingin menggunakan Lorem Ipsum, anda perlu memastikan bahwa tiada apa yang', 200.50),
  (2, 'PD1002', 'Television DXT', 'Ia menggunakan kamus yang mengandungi lebih 200 ayat Latin, bersama model dan struktur ayat Latin, untuk menghasilkan Lorem Ipsum yang munasabah.', 500.85),
  (3, 'PD1003', 'External Hard Disk', 'Ada banyak versi dari mukasurat-mukasurat Lorem Ipsum yang sedia ada, tetapi kebanyakkannya telah diubahsuai, lawak jenaka diselitkan, atau ayat ayat yang', 100.00);
```
6.  Перейти до каталогу `C:\xampp\htdocs\` та очистити його
7.  У зв'язку з припиненням підтримки PHP MySQL в 2011 році для роботи з базами даних все більш широке застосування знаходять PDO (PHP Data Objects) або MySqli.
8.  MySqli пропонує два способи з'єднання з базою даних: процедурний і об'єктно-орієнтований. Рекомендовано використовувати об'єктно-орієнтований. Процедурний схожий на (старий) MySql, тому для новачків його використання, можливо, буде краще, але варто пам'ятати, що їм користуватися не рекомендується.

```php
//процедурний стиль 
$mysqli = mysqli_connect('host','username','password','database_name'); 
//об'єктно-орієнтований стиль (рекомендується) 
$mysqli = new mysqli('host','username','password','database_name');
```

9.  Нижче показано відкриття з'єднання з базою даних об'єктно-орієнтованим способом. Цей спосіб буде використовуватися і в усіх наведених нижче прикладах.

    Отже, створіть файл config.php у який помістіть настпуний код:

```php
<?php
// Налаштування БД
$database_host = 'localhost';
$database_username = 'root';
$database_password = '';
$database_name = 'Products';

// Відкриваємо нове з'єднання з MySQL сервером
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);

// Виводимо помилку з'єднання
if ($mysqli->connect_error) {
    die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>
```
10. Створіть файл index.php у якому підключіть файл з настройками БД та виконуте представлені приклади.
11. Вибір (SELECT) результуючого ряду у вигляді асоціативного масиву. `mysqli_fetch_assoc()`: в наведеному нижче коді відбувається вилучення результуючого ряду у вигляді асоціативного масиву. Масив, що повертається містить рядки, отримані з бази даних, де імена стовпців будуть ключем, що використовується для доступу до внутрішніх даних. Як показано нижче, дані відображаються у вигляді HTML таблиці.

```PHP
<?php
$mysqli = new mysqli('host', 'username', 'password', 'database_name');

if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$results = $mysqli->query("SELECT id, product_code, product_desc, price FROM products");

print '<table border="1">';
while ($row = $results->fetch_assoc()) {
    print '<tr>';
    print '<td>' . $row["id"] . '</td>';
    print '<td>' . $row["product_code"] . '</td>';
    print '<td>' . $row["product_name"] . '</td>';
    print '<td>' . $row["product_desc"] . '</td>';
    print '<td>' . $row["price"] . '</td>';
    print '</tr>';
}
print '</table';

$results->free();
$mysqli->close();
?>
```
12. Вибір (SELECT) результуючого ряду у вигляді масиву (асоціативний, звичайний, або в обидва). Фукнція `fetch_array()`: повертає масив з об'єднаним функціоналом `mysqli_fetch_row` і `mysqli_fetch assoc`. Ця функція є розширеною версією функції `mysqli_fetch_row()`; для доступу до даних можна використовувати як рядок, так і числа.

```PHP
<?php
$mysqli = new mysqli('host', 'username', 'password', 'database_name');

if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$results = $mysqli->query("SELECT id, product_code, product_desc, price FROM products");

print '<table border="1">';

while ($row = $results->fetch_array()) {
    print '<tr>';
    print '<td>' . $row["id"] . '</td>';
    print '<td>' . $row["product_code"] . '</td>';
    print '<td>' . $row["product_name"] . '</td>';
    print '<td>' . $row["product_desc"] . '</td>';
    print '<td>' . $row["price"] . '</td>';
    print '</tr>';
}

print '</table>';

$results->free();
$mysqli->close();
?>
```
13. Вибір (SELECT) результуючого ряду у вигляді об'єкта. `fetch_object()`: щоб отримати результуючий набір у вигляді об'єкта, потрібно скористатися MySqli `fetch_object()`. Атрибути об'єкта будуть відображати імена полів, знайдених всередині результуючого набору.

```PHP
<?php
$mysqli = new mysqli('host', 'username', 'password', 'database_name');

if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$results = $mysqli->query("SELECT id, product_code, product_desc, price FROM products");

print '<table border="1">';

while ($row = $results->fetch_object()) {
    print '<tr>';
    print '<td>' . $row->id . '</td>';
    print '<td>' . $row->product_code . '</td>';
    print '<td>' . $row->product_name . '</td>';
    print '<td>' . $row->product_desc . '</td>';
    print '<td>' . $row->price . '</td>';
    print '</tr>';
}

print '</table>';

$mysqli->close();
?>
```
14. Вибір (SELECT) одиночного значення. Одиночне значення отримати з бази даних можна за допомогою `fetch_object()`

```PHP
<?php
$mysqli = new mysqli('host', 'username', 'password', 'database_name');

if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$product_name = $mysqli->query("SELECT product_name FROM products WHERE id = 1")->fetch_object()->product_name;

print $product_name;

$mysqli->close();
?>
```
15. Витягуємо (SELECT COUNT) кількість рядків в таблиці. Іноді потрібно дізнатися кількість рядків у таблиці, особливо при нумерації сторінок.

```PHP
<?php
$mysqli = new mysqli('host', 'username', 'password', 'database_name');

if ($mysqli->connect_error) {
    die('Error: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$results = $mysqli->query("SELECT COUNT(*) FROM users");
$get_total_rows = $results->fetch_row();

$mysqli->close();
?>
```
16. Вибір (SELECT) за допомогою шаблонів (prepared statements).

prepared statements \- спеціальний інструмент СУБД, що дозволяє прискорити послідовне виконання повторюваних запитів, побудованих за одним і тим же шаблоном.

Однією з особливостей MySqli є можливість використання вже написаних шаблонів: тобто запит досить написати один раз, після чого його можна багаторазово виконувати з різними параметрами. Використання вже написаних шаблонів покращує продуктивність для великих таблиць і складних запитів. Для запобігання попаданню шкідливого коду аналіз кожного запиту здійснюється сервером окремо.

Код нижче використовує шаблон (Prepared statement), щоб отримувати дані з бази даних. Заповнювач `?` в запиті SQL грає роль маркера і буде заміщений параметром, який, в свою чергу, може бути рядком, цілим числом, double або blob. У нашому випадку це рядок `$search_product`.

```PHP
<?php
$search_product = "PD1001"; // product id

// Створення prepared statement
$query = "SELECT id, product_code, product_desc, price FROM products WHERE product_code=?";
$statement = $mysqli->prepare($query);

// Параметри прив’язки для маркерів, де (s = string, i = integer, d = double, b = blob)
$statement->bind_param('s', $search_product);

// Виконання запиту
$statement->execute();

// Зв'язування результуючих змінних
$statement->bind_result($id, $product_code, $product_desc, $price);

print '<table border="1">';
// Вивід записів
while ($statement->fetch()) {
    print '<tr>';
    print '<td>' . $id . '</td>';
    print '<td>' . $product_code . '</td>';
    print '<td>' . $product_desc . '</td>';
    print '<td>' . $price . '</td>';
    print '</tr>';
}
print '</table>';

// Закриття з'єднання
$statement->close();
?>
```
17. Той самий запит із декількома параметрами:

```PHP
<?php
$search_ID = 1;
$search_product = "PD1001";

$query = "SELECT id, product_code, product_desc, price FROM products WHERE ID=? AND product_code=?";
$statement = $mysqli->prepare($query);
$statement->bind_param('is', $search_ID, $search_product);
$statement->execute();
$statement->bind_result($id, $product_code, $product_desc, $price);

print '<table border="1">';
while ($statement->fetch()) {
    print '<tr>';
    print '<td>' . $id . '</td>';
    print '<td>' . $product_code . '</td>';
    print '<td>' . $product_desc . '</td>';
    print '<td>' . $price . '</td>';
    print '</tr>';
}
print '</table';

$statement->close();
?>
```
18. Вставка (INSERT) запису. Код нижче вставляє в таблицю новий запис.

```php
<?php
// Значення, які потрібно вставити в таблицю бази даних
$product_code = '"' . $mysqli->real_escape_string('P1234') . '"';
$product_name = '"' . $mysqli->real_escape_string('42 inch TV') . '"';
$product_price = '"' . $mysqli->real_escape_string('600') . '"';

$insert_row = $mysqli->query("INSERT INTO products (product_code, product_name, price) VALUES ($product_code, $product_name, $product_price)");

if ($insert_row) {
    print 'Запит успішно виконаний! ID останнього вставленого запису: ' . $mysqli->insert_id . '<br />';
} else {
    die('Помилка: (' . $mysqli->errno . ') ' . $mysqli->error);
}
?>
```
19. Фрагмент коду нижче вставляє ті ж значення за допомогою шаблонів (prepared statement). Як ми вже говорили, шаблони надзвичайно ефективні проти SQL ін'єкцій. Для наведеного прикладу їх використання є оптимальним варіантом.

```PHP
<?php
$product_code = 'P1234';
$product_name = '42 inch TV';
$product_price = '600';

$query = "INSERT INTO products (product_code, product_name, price) VALUES (?, ?, ?)";
$statement = $mysqli->prepare($query);
$statement->bind_param('sss', $product_code, $product_name, $product_price);

if ($statement->execute()) {
    print 'Запит успішно виконаний! ID останнього вставленого запису: ' . $statement->insert_id . '<br />';
} else {
    die('Помилка: (' . $mysqli->errno . ') ' . $mysqli->error);
}

$statement->close();
?>
```
20. Вставка (INSERT) декількох записів. Вставка декількох рядків одночасно здійснюється шляхом включення ряду значень стовпців, де кожен ряд значень повинен бути у дужках і відділений від інших комою. Іноді потрібно дізнатися, скільки записів було вставлено, оновлено або видалено, для цього можна скористатися `$mysqli->affected_rows`.

```php
<?php
// product 1
$product_code1 = '"' . $mysqli->real_escape_string('P1') . '"';
$product_name1 = '"' . $mysqli->real_escape_string('Google Nexus') . '"';
$product_price1 = '"' . $mysqli->real_escape_string('149') . '"';

// product 2
$product_code2 = '"' . $mysqli->real_escape_string('P2') . '"';
$product_name2 = '"' . $mysqli->real_escape_string('Apple iPad 2') . '"';
$product_price2 = '"' . $mysqli->real_escape_string('217') . '"';

// product 3
$product_code3 = '"' . $mysqli->real_escape_string('P3') . '"';
$product_name3 = '"' . $mysqli->real_escape_string('Samsung Galaxy Note') . '"';
$product_price3 = '"' . $mysqli->real_escape_string('259') . '"';

// Insert multiple rows
$insert = $mysqli->query("INSERT INTO products(product_code, product_name, price) VALUES ($product_code1, $product_name1, $product_price1), ($product_code2, $product_name2, $product_price2), ($product_code3, $product_name3, $product_price3)");

if ($insert) {
    print 'Запит успішно виконаний! Всього ' . $mysqli->affected_rows . ' рядків додано.';
} else {
    die('Помилка: (' . $mysqli->errno . ') ' . $mysqli->error);
}
?>
```
21. Оновлення (Update)/видалення (Delete) записів. Принцип оновлення і видалення записів такий самий. Достатньо замінити рядок запиту на UPDATE або DELETE

```PHP
<?php
// Запит на оновлення
$results = $mysqli->query("UPDATE products SET product_name='52 inch TV', product_code='323343' WHERE ID=24");

// Запит на видалення
$results = $mysqli->query("DELETE FROM products WHERE ID=24");

if ($results) {
    print 'Запит успішно виконаний! Запис оновлено/видалено';
} else {
    print 'Помилка: (' . $mysqli->errno . ') ' . $mysqli->error;
}
?>

```
22. Оновлення за допомогою шаблонів (prepared statements). Приклад поновлення запису за допомогою шаблонів (prepared statements) наведено нижче.

```php
<?php
$product_name = '52 inch TV';
$product_code = '9879798';
$find_id = 24;

$query = "UPDATE products SET product_name=?, product_code=? WHERE ID=?";
$statement = $mysqli->prepare($query);
$results = $statement->bind_param('ssi', $product_name, $product_code, $find_id);

if ($results) {
    print 'Запит успішно виконаний! Запис оновлено';
} else {
    print 'Помилка: (' . $mysqli->errno . ') ' . $mysqli->error;
}
?>
```
23. Видалення старих записів. Видаленню піддаються всі записи, що знаходяться на сервері більше 1 дня; кількість днів можна задати самому.
```php
<?php
// Запит на видалення
$results = $mysqli->query("DELETE FROM products WHERE added_timestamp > (NOW() - INTERVAL 1 DAY)");

if ($results) {
    print 'Запит успішно виконаний! Видалено одноденні записи';
} else {
    print 'Помилка: (' . $mysqli->errno . ') ' . $mysqli->error;
}
?>
```

24. З частин коду зберіть повний сценарій
25. На основі лабораторної роботи №8 зробіть програму, яка зберігає дані про сутності, що відповідають вашому варіанту, в таблицю MySQL та виводить їх у табличному вигляді
26. Впевніться, що всі вихідні HTML-сторінки є валідними, використавши валідатор HTML-коду `https://validator.w3.org/`. За необхідності, виправити помилки та зауваження
27. Для проекту створіть папку "lab 11" в своєму репозиторії "web-progr" на Github. Завантажте кінцеві файли лабораторної роботи. 
28. Для кожного етапу роботи зробіть знімки екрану та додайте їх у звіт з описом кожного скіншота
29. Додайте програмний код завдання для самомтійного виконання
30. Дайте відповіді на контрольні запитання
31. Додайте в кінці звіту посилання на репозиторій та папку з лабораторною роботою
32. Збережіть звіт у форматі PDF
33. Додайте звіт до репозиторія

## Контрольні питання

1. Що таке бібліотека MySQLi і для чого вона використовується в PHP?
2. Які переваги використання MySQLi порівняно з застарілою бібліотекою MySQL в PHP?
3. Як встановити підключення до бази даних MySQL за допомогою MySQLi?
4. Як виконати запит SQL до бази даних з використанням MySQLi?
5. Як виконати операції вставки, оновлення та видалення даних в базі даних з MySQLi?
6. Як отримати результати запиту SELECT і обробити їх з використанням MySQLi?
7. Як здійснюється закриття підключення до бази даних і чому воно важливе при роботі з MySQLi?


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


