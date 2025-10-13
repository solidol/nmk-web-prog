<?php


class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all($this->pdo);
        echo "<h1>Список груп</h1>";
        echo '<a href="/groups/create">Додати групу</a>';
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Група</th></tr>";

        foreach ($groups as $group) {
            echo "<tr>";
            echo "<td>" . $group->columns->id . "</td>";
            echo "<td>" . $group->columns->title . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    public function show($id)
    {
        $group = Group::find($this->pdo, $id);
        echo "<h1>Список груп</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Група</th></tr>";


        echo "<tr>";
        echo "<td>" . $group->columns->id . "</td>";
        echo "<td>" . $group->columns->title . "</td>";

        echo "</tr>";
        echo "</table>";

        echo "<h1>Список студентів групи</h1>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Ім'я користувача</th></tr>";

        foreach ($group->students() as $user) {
            echo "<tr>";
            echo "<td>" . $user->columns->id . "</td>";
            echo "<td>" . $user->columns->fullname . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    public function edit($id)
    {
        $group = Group::find($this->pdo, $id);
    }

    public function update($id)
    {
        $group = Group::find($this->pdo, $id);
    }

    public function delete($id)
    {
        $group = Group::find($this->pdo, $id);
        $title = $group->columns->title;
        $group->delete();
        echo "<h1>Групу $title видалено</h1>";
    }

    public function create()
    {
        ?>
        <h1>Створення групи</h1>
        <form action="/groups/store" method="post">
            <div>
                Назва <input type="text" name="title">
            </div>
            <div>
                Рік <input type="text" name="year">
            </div>
            <div>
                <button type="submit">Зберегти</button>
            </div>
        </form>
        <?php
    }

    public function store()
    {
        $data['title'] = $_POST['title'];
        $data['year'] = $_POST['year'];
        $data['specialty_id'] = 0;
        Group::insert($this->pdo, $data);
        echo '<h1>Дані збережено</h1>';
        echo '<a href="/groups">До переліку груп</a>';
    }
}