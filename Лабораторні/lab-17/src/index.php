<?php
include "core/config.php";
include "core/connection.php";
include "core/Model.php";
include "models/User.php";
include "models/Student.php";
include "models/Group.php";

// Отримання всіх користувачів


if (isset($_GET["id"])) {
    $student = Student::find($pdo, $_GET["id"]);
    echo "<h1>Список студентів</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Група</th></tr>";

    echo "<tr>";
    echo "<td>" . $student->columns->id . "</td>";
    echo "<td>" . $student->columns->fullname . "</td>";
    echo "<td>" . $student->group()->columns->title . "</td>";
    echo "</tr>";

    echo "</table>";
} else {


    if (count(Student::all($pdo)) > 0) {
        echo "<h1>Список користувачів</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Група</th></tr>";

        foreach (Student::all($pdo) as $student) {
            echo "<tr>";
            echo "<td>" . $student->columns->id . "</td>";
            echo "<td>" . $student->columns->fullname . "</td>";
            echo "<td>" . $student->group()->columns->title . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "У базі даних немає користувачів.";
    }
}
