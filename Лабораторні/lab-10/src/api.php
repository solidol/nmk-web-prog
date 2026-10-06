<?php
declare(strict_types=1);
require __DIR__ . '/common.php';

set_error_handler(static function (int $severity, string $message): never {
    throw new ErrorException($message, 0, $severity);
});
try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $resource = queryString('resource', 'tasks');
    if ($resource === 'auth') {
        allowMethods($method, ['GET']);
        // Лише публічні демонстраційні дані, не облікові записи користувачів.
        $user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $password = $_SERVER['PHP_AUTH_PW'] ?? '';
        if ($user !== 'student' || $password !== 'demo-pass') {
            header('WWW-Authenticate: Basic realm="Lab10", charset="UTF-8"');
            throw new ApiError(401, 'Потрібні демонстраційні Basic Auth дані.');
        }
        respond(200, ['data' => ['authenticated' => true, 'user' => 'student']]);
    }
    if ($resource === 'echo') {
        allowMethods($method, ['POST']);
        if (!in_array(contentType(), ['application/x-www-form-urlencoded', 'multipart/form-data'], true)) {
            throw new ApiError(415, 'Оберіть urlencoded або form-data.');
        }
        $length = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
        if ($length > 100 * 1024) {
            throw new ApiError(413, 'Форма перевищує 100 KiB.');
        }
        $fields = [];
        foreach ($_POST as $name => $value) {
            if (!is_string($value) || strlen($value) > 1000) {
                throw new ApiError(422, 'Поля форми мають бути рядками до 1000 байтів.');
            }
            $fields[$name] = $value;
        }
        $file = null;
        if ($_FILES !== []) {
            if (count($_FILES) !== 1 || !isset($_FILES['attachment'])) {
                throw new ApiError(422, 'Допустиме одне файлове поле attachment.');
            }
            $upload = $_FILES['attachment'];
            if (!is_array($upload) || !isset($upload['error'], $upload['size'], $upload['tmp_name'], $upload['name'])
                || !is_int($upload['error']) || $upload['error'] !== UPLOAD_ERR_OK
                || !is_int($upload['size']) || $upload['size'] < 1 || $upload['size'] > 65536
                || !is_string($upload['name']) || !is_string($upload['tmp_name'])
                || !is_uploaded_file($upload['tmp_name'])) {
                throw new ApiError(422, 'Файл має успішно передатися й мати розмір 1–65536 байтів.');
            }
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
            $file = ['name' => $upload['name'], 'size' => $upload['size'], 'detected_mime' => $mime];
        }
        respond(200, ['data' => ['method' => $method, 'content_type' => contentType(), 'fields' => (object) $fields, 'file' => $file]]);
    }
    if ($resource !== 'tasks') {
        throw new ApiError(404, 'Ресурс не знайдено.');
    }
    allowMethods($method, ['GET', 'POST', 'PUT', 'DELETE']);
    $id = taskId();
    if ($method === 'GET') {
        $done = queryString('done');
        if ($done !== null && !in_array($done, ['0', '1'], true)) {
            throw new ApiError(400, 'done у query має бути 0 або 1.');
        }
        if ($id !== null && $done !== null) {
            throw new ApiError(400, 'Для одного запису не додавайте фільтр done.');
        }
        $result = withStore(function (array &$store) use ($id, $done): array {
            if ($id !== null) {
                foreach ($store['tasks'] as $task) {
                    if ($task['id'] === $id) {
                        return ['data' => $task];
                    }
                }
                throw new ApiError(404, 'Завдання не знайдено.');
            }
            $tasks = $store['tasks'];
            if ($done !== null) {
                $tasks = array_values(array_filter($tasks, fn(array $task): bool => $task['done'] === ($done === '1')));
            }
            return ['data' => $tasks, 'meta' => ['count' => count($tasks)]];
        }, false);
        respond(200, $result);
    }
    if ($method === 'POST') {
        if ($id !== null) {
            throw new ApiError(400, 'POST створює запис без id у query.');
        }
        $body = taskBody(false);
        $task = withStore(function (array &$store) use ($body): array {
            if (count($store['tasks']) >= 100 || $store['next_id'] > 1000000000) {
                throw new ApiError(409, 'Досягнуто межу навчального сховища.');
            }
            $task = ['id' => $store['next_id']++] + $body;
            $store['tasks'][] = $task;
            return $task;
        }, true);
        header('Location: api.php?resource=tasks&id=' . $task['id']);
        respond(201, ['data' => $task]);
    }
    if ($id === null) {
        throw new ApiError(400, 'Для PUT і DELETE потрібен id.');
    }
    $body = $method === 'PUT' ? taskBody(true) : null;
    $result = withStore(function (array &$store) use ($id, $body, $method): ?array {
        foreach ($store['tasks'] as $index => $task) {
            if ($task['id'] === $id) {
                if ($method === 'DELETE') {
                    array_splice($store['tasks'], $index, 1);
                    return null;
                }
                $store['tasks'][$index] = ['id' => $id] + $body;
                return $store['tasks'][$index];
            }
        }
        throw new ApiError(404, 'Завдання не знайдено.');
    }, true);
    respond($method === 'DELETE' ? 204 : 200, $result === null ? null : ['data' => $result]);
} catch (ApiError $exception) {
    respond($exception->status, ['error' => ['message' => $exception->getMessage()]]);
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    respond(500, ['error' => ['message' => 'Внутрішня помилка. Перевірте сховище, розширення та журнал PHP.']]);
}
