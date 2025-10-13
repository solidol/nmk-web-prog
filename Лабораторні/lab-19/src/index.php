<?php
include "bootstrap.php";


?>
<!DOCTYPE html>
<html lang="ua">

<head>

</head>

<body>
    <?php

$router = new Router();
//

$router->addRoute('GET', '/groups', [GroupController::class,$pdo,'index']);
$router->addRoute('GET', '/groups/show/{id}', [GroupController::class,$pdo,'show']);
$router->addRoute('GET', '/groups/create', [GroupController::class,$pdo,'create']);
$router->addRoute('POST', '/groups/store', [GroupController::class,$pdo,'store']);

$router->addRoute('GET', '/students', [StudentController::class,$pdo,'index']);
$router->addRoute('GET', '/students/show/{id}', [StudentController::class,$pdo,'show']);

$router->addRoute('GET', '/users', [UserController::class,$pdo,'index']);
$router->addRoute('GET', '/users/show/{id}', [UserController::class,$pdo,'show']);

// Запустити роутер
$router->handleRequest();


    ?>
</body>

</html>