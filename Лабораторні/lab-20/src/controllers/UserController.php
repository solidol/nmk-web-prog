<?php


class UserController extends Controller
{
    public function index()
    {
        $users = User::all($this->pdo);
        $template = 'users_index.php';
        include "views/layout.php";
    }

    public function show($id)
    {
        $user = User::find($this->pdo, $id);
        $template = 'users_show.php';
    }
}