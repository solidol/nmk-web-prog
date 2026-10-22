<?php
declare(strict_types=1);
require __DIR__ . '/connection.php';
if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Запустіть php demo.php у консолі проєкту.');
}
$pdo = null;
$stmt = null;
$exitCode = 0;
try {
    $pdo = connectDatabase();
    $pdo->beginTransaction();
    $name = "Навчальне ім’я 'Код' & друзі";
    $email = 'demo-' . bin2hex(random_bytes(6)) . '@example.test';
    $stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
    $stmt->execute(['name' => $name, 'email' => $email]);
    // Для цього INT-ключа перетворення безпечне у 64-бітному навчальному середовищі.
    $id = (int) $pdo->lastInsertId();
    $stmt->closeCursor();

    $stmt = $pdo->prepare('SELECT id, name, email FROM users WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row === false || $row['name'] !== $name || $row['email'] !== $email) {
        throw new RuntimeException('Не підтверджено INSERT і SELECT.');
    }
    $stmt->closeCursor();

    $newEmail = 'updated-' . bin2hex(random_bytes(6)) . '@example.test';
    $stmt = $pdo->prepare('UPDATE users SET email = :email WHERE id = :id');
    $stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() !== 1) {
        throw new RuntimeException('Не підтверджено UPDATE.');
    }
    $stmt->closeCursor();
    $stmt = $pdo->prepare('SELECT email FROM users WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetchColumn() !== $newEmail) {
        throw new RuntimeException('Повторний SELECT не підтвердив зміну.');
    }
    $stmt->closeCursor();

    $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() !== 1) {
        throw new RuntimeException('Не підтверджено DELETE.');
    }
    $stmt->closeCursor();
    $stmt = $pdo->prepare('SELECT id FROM users WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetch() !== false) {
        throw new RuntimeException('Видалений запис знайдено.');
    }
    $stmt->closeCursor();
    $pdo->rollBack();
    echo "INSERT → SELECT → UPDATE → SELECT → DELETE перевірено; транзакцію відкочено.\n";
} catch (Throwable $exception) {
    if ($pdo !== null && $pdo->inTransaction()) {
        try {
            $pdo->rollBack();
        } catch (Throwable $rollbackError) {
            error_log('lec09 rollback: code=' . $rollbackError->getCode());
        }
    }
    error_log('lec09 demo: code=' . $exception->getCode());
    fwrite(STDERR, "Перевірте драйвер, реквізити, права й таблицю users.\n");
    $exitCode = 1;
} finally {
    // Спершу прибираємо statement, який також утримує посилання на з’єднання.
    $stmt = null;
    $pdo = null;
}
exit($exitCode);
