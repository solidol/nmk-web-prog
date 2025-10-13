<?php


class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all($this->pdo);
        $template = 'groups_index.php';
        include "views/layout.php";
    }

    public function show($id)
    {
        $group = Group::find($this->pdo, $id);
        $template = 'groups_show.php';
        include "views/layout.php";
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
        $template = 'groups_create.php';
        include "views/layout.php";
    }

    public function store()
    {
        $data['title'] = $_POST['title'];
        $data['year'] = $_POST['year'];
        $data['specialty_id'] = 0;
        Group::insert($this->pdo, $data);
        $template = 'groups_store.php';
        include "views/layout.php";
    }
}