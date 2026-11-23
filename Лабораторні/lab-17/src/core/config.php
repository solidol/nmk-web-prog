<?php
// Значення середовища замінюють наведені навчальні налаштування.
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_NAME') ?: 'lab17';
$username = getenv('DB_USER') ?: 'lab17_user';
$password = (string) getenv('DB_PASSWORD');
