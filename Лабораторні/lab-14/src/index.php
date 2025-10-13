<?php
include "User.php";
try {
    $pdo = new PDO("mysql:host=хост;dbname=ім'я_бази_даних", "користувач", "пароль");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Отримання всіх користувачів
    $users = User::all($pdo);
    
    if (count($users) > 0) {
        echo "<h1>Список користувачів</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Email</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user->id . "</td>";
            echo "<td>" . $user->username . "</td>";
            echo "<td>" . $user->email . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "У базі даних немає користувачів.";
    }
} catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
}
?>
