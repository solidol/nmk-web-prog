<?php
session_start();
$_SESSION['user'] = "Student";
?>
<html>
<body>
    <p>Сесія відкрита. Ім'я користувача: <?php echo $_SESSION['user']; ?></p>
</body>
</html>
