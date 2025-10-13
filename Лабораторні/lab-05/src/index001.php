<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LR 5</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        div {
            margin-bottom: 10px;
        }
        input[type="text"] {
            padding: 5px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h1>Вхід на сайт</h1>

    <div>
        <?php
        $login = isset($_POST['login']) ? htmlspecialchars($_POST['login']) : "";
        $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : "";

        if ($login || $password) {
            echo "<p>Ваш логін: $login</p>";
            echo "<p>Ваш пароль: $password</p>";
        }
        ?>
    </div>

    <form method="POST">
        <div>
            Логін: <input type="text" name="login" required>
        </div>
        <div>
            Пароль: <input type="text" name="password" required>
        </div>
        <div>
            <input type="submit" value="Увійти">
        </div>
    </form>

</body>
</html>
