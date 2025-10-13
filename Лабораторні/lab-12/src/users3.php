<?php
if (isset($_GET['id'])) {
    try {
        $pdo = new PDO("mysql:host=хост;dbname=ім'я_бази_даних", "користувач", "пароль");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Помилка підключення до бази даних: " . $e->getMessage();
        exit();
    }

    $userId = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<h1>Детальна інформація про користувача</h1>";
            echo "<p>ID: " . $user['id'] . "</p>";
            echo "<p>Ім'я: " . $user['username'] . "</p>";
            echo "<p>Email: " . $user['email'] . "</p>";
        } else {
            echo "Користувача з таким ID не знайдено.";
        }
    } else {
        echo "Помилка при виборці інформації про користувача.";
    }
} else {
    echo "ID користувача не вказано.";
}
?>
