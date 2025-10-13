<!DOCTYPE html>
<html>

<head>
    <title>Додавання користувача</title>
</head>

<body>
    <h1>Додати нового користувача</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">Ім'я користувача:</label>
        <input type="text" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Додати користувача">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Отримати дані з форми
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Підключення до бази даних
        try {
            $pdo = new PDO("mysql:host=хост;dbname=ім'я_бази_даних", "користувач", "пароль");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Помилка підключення до бази даних: " . $e->getMessage();
            exit();
        }

        // Додавання користувача до бази даних
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));

        if ($stmt->execute()) {
            echo "Користувач доданий успішно!";
        } else {
            echo "Помилка при додаванні користувача.";
        }
    }
    ?>
</body>

</html>