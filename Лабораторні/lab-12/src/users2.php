<?php
try {
    $pdo = new PDO("mysql:host=хост;dbname=ім'я_бази_даних", "користувач", "пароль");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
    exit();
}

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    echo "<h1>Список користувачів</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Ім'я</th><th>Email</th></tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "У базі даних немає користувачів.";
}
?>
