<?php


class StudentController extends Controller
{
    public function index()
    {
        $users = Student::all($this->pdo);
        echo "<h1>Список користувачів</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Група</th></tr>";

        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user->columns->id . "</td>";
            echo "<td>" . $user->columns->fullname . "</td>";
            echo "<td>" . $user->group()->columns->title . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    public function show($id)
    {
        $user = Student::find($this->pdo, $id);
        echo "<h1>Список користувачів</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я користувача</th><th>Група</th></tr>";


        echo "<tr>";
        echo "<td>" . $user->columns->id . "</td>";
        echo "<td>" . $user->columns->fullname . "</td>";
        echo "<td>" . $user->group()->columns->title . "</td>";
        echo "</tr>";


        echo "</table>";
    }
}