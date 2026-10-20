<?php
declare(strict_types=1);
require __DIR__ . '/common.php';
if (PHP_SAPI !== 'cli') {
    http_response_code(405);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Запустіть php crud-demo.php у терміналі.');
}
$db = null;
$inTransaction = false;
$exitCode = 0;
try {
    $product = validateProduct(['product_code' => 'DEMO-' . strtoupper(bin2hex(random_bytes(5))),
        'product_name' => "Набір 'Код' & друзі", 'product_desc' => '<b>Навчальний опис</b>', 'price' => '125.50']);
    $db = connectDatabase();
    mysqli_begin_transaction($db);
    $inTransaction = true;
    $stmt = mysqli_prepare($db,
        'INSERT INTO products (product_code, product_name, product_desc, price) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $product['product_code'], $product['product_name'], $product['product_desc'], $product['price']);
    mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($db);
    mysqli_stmt_close($stmt);
    $rows = findProducts($db, $product['product_code']);
    if (count($rows) !== 1 || $rows[0]['product_name'] !== $product['product_name'] || $rows[0]['price'] !== '125.50') {
        throw new RuntimeException('Не підтверджено додавання.');
    }
    $price = '130.00';
    $stmt = mysqli_prepare($db, 'UPDATE products SET price = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'si', $price, $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) !== 1) {
        throw new RuntimeException('Не підтверджено оновлення.');
    }
    mysqli_stmt_close($stmt);
    if (findProducts($db, $product['product_code'])[0]['price'] !== '130.00') {
        throw new RuntimeException('Оновлена ціна не збереглася.');
    }
    $stmt = mysqli_prepare($db, 'DELETE FROM products WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) !== 1) {
        throw new RuntimeException('Не підтверджено видалення.');
    }
    mysqli_stmt_close($stmt);
    if (findProducts($db, $product['product_code']) !== []) {
        throw new RuntimeException('Видалений товар знайдено.');
    }
    mysqli_rollback($db);
    $inTransaction = false;
    echo "INSERT → SELECT → UPDATE → SELECT → DELETE перевірено; транзакцію відкочено.\n";
} catch (Throwable $exception) {
    if ($db !== null && $inTransaction) {
        try {
            mysqli_rollback($db);
        } catch (Throwable $rollbackError) {
            error_log('lab11 rollback: code=' . $rollbackError->getCode());
        }
    }
    error_log('lab11 demo: code=' . $exception->getCode());
    fwrite(STDERR, "Перевірте конфігурацію, права доступу й таблицю products.\n");
    $exitCode = 1;
} finally {
    if ($db !== null) {
        mysqli_close($db);
    }
}
exit($exitCode);
