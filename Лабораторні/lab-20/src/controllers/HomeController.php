<?php


class HomeController extends Controller
{
    public function home()
    {
        $template = 'index.php';
        include "views/layout.php";

    }

}