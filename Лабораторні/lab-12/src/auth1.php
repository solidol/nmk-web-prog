<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        $pdo = new PDO("mysql:host=хост;dbname=ім'я_бази_даних", "користувач", "пароль");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Помилка підключення до бази даних: " . $e->getMessage();
        exit();
    }

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION["user_id"] = $user['id'];
            echo "Авторизація успішна. Ви увійшли як " . $user['username'];
        } else {
            echo "Неправильне ім'я користувача або пароль.";
        }
    } else {
        echo "Помилка при авторизації.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Авторизація</title>
</head>
<body>
    <h1>Вхід до системи</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <label for="username">Ім'я користувача:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Увійти">
    </form>
</body>
</html>
