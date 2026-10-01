<?php
$username = isset($_COOKIE["username"]) ? $_COOKIE["username"] : "Немає";
$age = isset($_COOKIE["age"]) ? $_COOKIE["age"] : "Немає";
?>
<html>
<body>
    <p>Ім'я: <?php echo htmlspecialchars($username); ?></p>
    <p>Вік: <?php echo htmlspecialchars($age); ?></p>
</body>
</html>
