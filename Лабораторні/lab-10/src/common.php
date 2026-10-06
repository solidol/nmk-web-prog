<?php
declare(strict_types=1);

final class ApiError extends RuntimeException
{
    public function __construct(public int $status, string $message)
    {
        parent::__construct($message);
    }
}

function respond(int $status, ?array $body = null): never
{
    $json = $body === null ? '' : json_encode($body,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    http_response_code($status);
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    if ($status !== 204) {
        header('Content-Type: application/json; charset=UTF-8');
        echo $json;
    }
    exit;
}

function queryString(string $key, ?string $default = null): ?string
{
    $value = $_GET[$key] ?? $default;
    if ($value !== null && !is_string($value)) {
        throw new ApiError(400, "Параметр $key має бути рядком.");
    }
    return $value;
}

function taskId(): ?int
{
    $raw = queryString('id');
    if ($raw === null) {
        return null;
    }
    $id = filter_var($raw, FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 1000000000]]);
    if (!ctype_digit($raw) || $id === false) {
        throw new ApiError(400, 'id має бути додатним цілим числом до 1000000000.');
    }
    return $id;
}

function contentType(): string
{
    return strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
}

function taskBody(bool $requireDone): array
{
    if (contentType() !== 'application/json') {
        throw new ApiError(415, 'Для цього запиту потрібен Content-Type: application/json.');
    }
    $raw = file_get_contents('php://input', false, null, 0, 16385);
    if ($raw === false) {
        throw new RuntimeException('Не вдалося прочитати тіло запиту.');
    }
    if (strlen($raw) > 16384) {
        throw new ApiError(413, 'JSON-тіло перевищує 16 KiB.');
    }
    try {
        $decoded = json_decode($raw, false, 32, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        throw new ApiError(400, 'Некоректний JSON або UTF-8.');
    }
    if (!$decoded instanceof stdClass) {
        throw new ApiError(422, 'Очікується JSON-об’єкт із полями title та done.');
    }
    $body = (array) $decoded;
    if (array_diff(array_keys($body), ['title', 'done']) !== []) {
        throw new ApiError(422, 'Дозволено лише поля title та done; id генерує сервер.');
    }
    $title = $body['title'] ?? null;
    if (!is_string($title) || !mb_check_encoding($title, 'UTF-8')
        || mb_strlen(trim($title), 'UTF-8') < 1 || mb_strlen(trim($title), 'UTF-8') > 80) {
        throw new ApiError(422, 'title має бути рядком від 1 до 80 символів після trim().');
    }
    if (($requireDone && !array_key_exists('done', $body))
        || (array_key_exists('done', $body) && !is_bool($body['done']))) {
        throw new ApiError(422, 'done має бути JSON-значенням true або false. Для PUT поле обов’язкове.');
    }
    return ['title' => trim($title), 'done' => $body['done'] ?? false];
}

function dataPath(): string
{
    // Окреме сховище для кожної копії проєкту, поза каталогом вебсервера.
    $dir = sys_get_temp_dir() . '/php-lab10-' . substr(hash('sha256', __DIR__), 0, 12);
    return $dir . '/tasks.json';
}

function validateStore(mixed $store): array
{
    if (!is_array($store) || !isset($store['next_id'], $store['tasks'])
        || !is_int($store['next_id']) || $store['next_id'] < 1
        || !is_array($store['tasks']) || !array_is_list($store['tasks'])) {
        throw new RuntimeException('Некоректна структура сховища.');
    }
    $ids = [];
    foreach ($store['tasks'] as $task) {
        if (!is_array($task) || !isset($task['id'], $task['title'], $task['done'])
            || !is_int($task['id']) || $task['id'] < 1 || $task['id'] >= $store['next_id']
            || !is_string($task['title']) || !is_bool($task['done'])
            || in_array($task['id'], $ids, true)) {
            throw new RuntimeException('Некоректний запис у сховищі.');
        }
        $ids[] = $task['id'];
    }
    return $store;
}

function withStore(callable $action, bool $write): mixed
{
    $path = dataPath();
    if (!is_file($path)) {
        throw new RuntimeException('Сховище не підготовлено: запустіть php reset.php.');
    }
    $handle = fopen($path, 'r+b');
    if ($handle === false) {
        throw new RuntimeException('Не вдалося відкрити сховище.');
    }
    try {
        // Блокування охоплює весь цикл читання, зміни й запису.
        if (!flock($handle, LOCK_EX)) {
            throw new RuntimeException('Не вдалося заблокувати сховище.');
        }
        $text = stream_get_contents($handle);
        if ($text === false) {
            throw new RuntimeException('Не вдалося прочитати сховище.');
        }
        $store = validateStore(json_decode($text, true, 32, JSON_THROW_ON_ERROR));
        $result = $action($store);
        if ($write) {
            $json = json_encode(validateStore($store), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
            if (!rewind($handle) || !ftruncate($handle, 0) || fwrite($handle, $json) !== strlen($json) || !fflush($handle)) {
                throw new RuntimeException('Не вдалося зберегти сховище.');
            }
        }
        return $result;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}

function allowMethods(string $method, array $allowed): void
{
    if (!in_array($method, $allowed, true)) {
        header('Allow: ' . implode(', ', $allowed));
        throw new ApiError(405, 'Метод не підтримується для цього ресурсу.');
    }
}
