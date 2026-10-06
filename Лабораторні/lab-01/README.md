[Перелік усіх робіт](../README.md)

# Лабораторна робота №1. Підготовка до роботи web-серверу та запуск сценаріїв

## Мета роботи

Навчитися налаштовувати OSPanel 6+ із PHP 8.1 (або альтернативний XAMPP із PHP 8.1), запускати вебсервер і перевіряти виконання PHP-сценарію.

## Обладнання

Основне навчальне середовище — **OSPanel 6+ з PHP 8.1**. Альтернатива — **XAMPP із PHP 8.1**. Налаштування домену, каталогів і консолі наведено в [пам’ятці середовища](../../ENVIRONMENT.md).

Персональний комп'ютер. OSPanel 6+ із PHP 8.1 (альтернатива — XAMPP із PHP 8.1). Текстовий редактор Sublime Text 3 або IDE NetBeans. Web-браузер Chrome, Firefox, Opera

## Хід роботи

1. Установіть OSPanel 6+ та виберіть PHP 8.1 для навчального проєкту. Альтернатива — XAMPP із PHP 8.1.
2. Відкрийте панель керування обраного середовища та налаштуйте локальний проєкт за [пам’яткою](../../ENVIRONMENT.md).
3. Запустіть вебсервер проєкту й модуль PHP 8.1.
4. Створіть каталог `lab-01` у публічному каталозі проєкту; для XAMPP — `C:\xampp\htdocs\lab-01`. Інші проєкти не видаляйте.
5. Визначте адресу роботи: наприклад, `http://web-prog.local/lab-01/`; для XAMPP — `http://localhost/lab-01/`.
6. Створіть `index.php` у каталозі лабораторної.
7.  У файл `index.php` помістити наступні рядки:
```php 
<?php  
phpinfo();  
?>  
```
    
8. Відкрийте визначену адресу роботи й переконайтеся, що сторінка phpinfo() показує PHP 8.1. Після перевірки приберіть діагностичний виклик.
9.  Profit! Вітаю! Ви створили перший сценарій на PHP
10. Провести аналіз сторінки phpinfo(), зробити короткий опис кожної секції параметрів цієї сторінки та додати їх у звіт.
15. Для кожного етапу роботи зробити знімки екрану та додати їх у звіт з описом кожного скіншота
16. Дати відповіді на контрольні запитання
17. Зберегти звіт у форматі PDF

## Контрольні питання

1.  Що таке web-сервер?
2.  Які програмні продукти подібні до Apache ви знаєте?
3.  Для чого необхідно вказувати `http://` перед адресою сайту?
4.  Чому не можна просто запустити сценарій php подвійним кліком з каталогу, де він розташований?
5.  Яке середовище є основним у курсі та яка дозволена альтернатива?
6.  Що означають записи `127.0.0.1` та `localhost`?

## Джерела

1. OSPanel. [Official documentation](https://github.com/OSPanel/OpenServerPanel/wiki).
2. Apache Friends. [XAMPP FAQs for Windows](https://www.apachefriends.org/faq_windows.html).
3. PHP Documentation Group. [phpinfo](https://www.php.net/manual/en/function.phpinfo.php).
4. PHP Documentation Group. [Installation on Windows](https://www.php.net/manual/en/install.windows.php).
5. PHP Documentation Group. [Built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php).

## Довідники та додаткові матеріали

1. [PHP Manual](https://www.php.net/manual/en/)
2. [XAMPP](https://www.apachefriends.org/ru/index.html)
3. [Apache HTTP server project](https://httpd.apache.org/)
4. [OpenServer Panel](https://ospanel.io/)
5. [EnginX](https://enginx.io/)