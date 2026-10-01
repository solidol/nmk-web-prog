<?php
session_start();
if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 1;
} else {
    $_SESSION['counter']++;
}
?>
<html>
<body>
    <p>Ви відвідали цю сторінку <?php echo $_SESSION['counter']; ?> раз(ів).</p>
</body>
</html>
