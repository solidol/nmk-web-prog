<?php
declare(strict_types=1);
require __DIR__ . '/connection.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Запустіть php demo.php у терміналі середовища проєкту.');
}
$db = null;
$transactionStarted = false;
$exitCode = 0;
try {
    $db = connectDatabase();
    $db->begin_transaction();
    $transactionStarted = true;

    $title = "Навчальний набір 'Код' & друзі";
    $stock = 10;
    $stmt = $db->prepare('INSERT INTO products (title, stock) VALUES (?, ?)');
    $stmt->bind_param('si', $title, $stock);
    $stmt->execute();
    $id = $db->insert_id;
    $stmt->close();

    $stock = 12;
    $stmt = $db->prepare('UPDATE products SET stock = ? WHERE id = ?');
    $stmt->bind_param('ii', $stock, $id);
    $stmt->execute();
    if ($stmt->affected_rows !== 1) {
        throw new RuntimeException('Не підтверджено оновлення створеного рядка.');
    }
    $stmt->close();

    $stmt = $db->prepare('SELECT title, stock FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($foundTitle, $foundStock);
    if ($stmt->fetch() !== true || $foundTitle !== $title || $foundStock !== 12) {
        throw new RuntimeException('Повторне читання не підтвердило дані.');
    }
    $stmt->close();

    $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if ($stmt->affected_rows !== 1) {
        throw new RuntimeException('Не підтверджено видалення створеного рядка.');
    }
    $stmt->close();

    // Відкочуємо демонстрацію: початкові записи залишаються незмінними.
    $db->rollback();
    $transactionStarted = false;
    echo "INSERT, UPDATE, SELECT, DELETE перевірено. Транзакцію відкочено.\n";
} catch (Throwable $exception) {
    if ($db !== null && $transactionStarted) {
        try {
            $db->rollback();
        } catch (Throwable $rollbackError) {
            error_log('lec-08 rollback: code=' . $rollbackError->getCode());
        }
    }
    error_log('lec-08 demo: code=' . $exception->getCode());
    fwrite(STDERR, "Перевірте параметри середовища, таблицю products і доступність MariaDB.\n");
    $exitCode = 1;
} finally {
    if ($db !== null) {
        $db->close();
    }
}
exit($exitCode);
