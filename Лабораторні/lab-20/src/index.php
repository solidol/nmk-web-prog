<?php
include "bootstrap.php";


$router = new Router();
//

$router->addRoute('GET', '/', [HomeController::class,$pdo,'home']);
$router->addRoute('GET', '/groups', [GroupController::class,$pdo,'index']);
$router->addRoute('GET', '/groups/{id}/show', [GroupController::class,$pdo,'show']);
$router->addRoute('GET', '/groups/create', [GroupController::class,$pdo,'create']);
$router->addRoute('POST', '/groups/store', [GroupController::class,$pdo,'store']);

$router->addRoute('GET', '/students', [StudentController::class,$pdo,'index']);
$router->addRoute('GET', '/students/{id}/show', [StudentController::class,$pdo,'show']);

$router->addRoute('GET', '/users', [UserController::class,$pdo,'index']);
$router->addRoute('GET', '/users/{id}/show', [UserController::class,$pdo,'show']);

// Запустити роутер
$router->handleRequest();


