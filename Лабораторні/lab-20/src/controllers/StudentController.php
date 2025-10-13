<?php


class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all($this->pdo);
        $template = 'students_index.php';
        include "views/layout.php";

    }

    public function show($id)
    {
        $student = Student::find($this->pdo, $id);
        $template = 'students_show.php';
        include "views/layout.php";
    }
}