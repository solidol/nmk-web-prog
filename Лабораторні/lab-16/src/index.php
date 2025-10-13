<?php
include "core/config.php";
include "core/connection.php";
include "core/Model.php";
include "models/User.php";
include "models/Student.php";
include "models/Group.php";

// Отримання всіх користувачів


if (isset($_GET["id"])) {
    $user = User::find($pdo, $_GET["id"]);
    echo "<h1>Список користувачів</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Email</th></tr>";

    echo "<tr>";
    echo "<td>" . $user->columns->id . "</td>";
    echo "<td>" . $user->columns->username . "</td>";
    echo "<td>" . $user->columns->email . "</td>";
    echo "</tr>";

    echo "</table>";
} else {


    if (count(User::all($pdo)) > 0) {
        echo "<h1>Список користувачів</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Email</th></tr>";

        foreach (User::all($pdo) as $user) {
            echo "<tr>";
            echo "<td>" . $user->columns->id . "</td>";
            echo "<td>" . $user->columns->username . "</td>";
            echo "<td>" . $user->columns->email . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "У базі даних немає користувачів.";
    }
}
