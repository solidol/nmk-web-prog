<?php
// Заготовка отримання даних; перевірку облікових даних додає студент.
$errors = [];
$login = '';
$received = false;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $rawLogin = $_POST['login'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!is_string($rawLogin) || !is_string($password)) {
        $errors[] = 'Логін і пароль мають бути рядками.';
    } else {
        $login = trim($rawLogin);
        if ($login === '' || $password === '') {
            $errors[] = 'Заповніть логін і пароль.';
        } else {
            $received = true;
        }
    }
}

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна робота 5 — отримання даних</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        form { margin-top: 20px; }
        .field { margin-bottom: 10px; }
        label { display: block; }
        input { padding: 5px; width: 100%; max-width: 300px; box-sizing: border-box; }
        button { padding: 5px 10px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Форма входу</h1>
    <p>Навчальна заготовка: дані отримуються без створення сеансу входу.</p>

    <?php if ($errors !== []): ?>
        <ul role="alert">
            <?php foreach ($errors as $error): ?>
                <li><?= escapeHtml($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($received): ?>
        <p>Дані отримано для перевірки. Логін: <?= escapeHtml($login) ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="field">
            <label for="login">Логін</label>
            <input id="login" type="text" name="login"
                   value="<?= escapeHtml($login) ?>" required>
        </div>
        <div class="field">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required>
        </div>
        <button type="submit">Надіслати</button>
    </form>
</body>
</html>
