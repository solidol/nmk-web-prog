
<?php

class Model
{
    protected PDO $pdo;
    protected string $table;
    protected array $fields = [];
    protected array $attributes = [];

    // Назва таблиці для дочірніх класів
    protected static string $defaultTable = '';

    public function __construct(
        PDO $pdo,
        string $table = '',
        array $data = []
    ) {
        $this->pdo = $pdo;
        $this->table = $table ?: static::$defaultTable;

        // Перевірка назви таблиці
        if (!preg_match('/\A[a-zA-Z_][a-zA-Z0-9_]*\z/', $this->table)) {
            throw new InvalidArgumentException('Некоректна назва таблиці.');
        }

        // Автоматично отримуємо поля таблиці
        $stmt = $pdo->query("SHOW COLUMNS FROM `{$this->table}`");
        $this->fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt->closeCursor();

        // Створюємо атрибути для всіх полів
        $this->attributes = array_fill_keys($this->fields, null);

        // Заповнюємо передані дані
        foreach ($data as $name => $value) {
            if (array_key_exists($name, $this->attributes)) {
                $this->attributes[$name] = $value;
            }
        }

        if (isset($this->attributes['id'])) {
            $this->attributes['id'] = (int) $this->attributes['id'];
        }
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        if ($name !== 'id' && in_array($name, $this->fields, true)) {
            $this->attributes[$name] = $value;
        }
    }

    // СТАТИЧНІ МЕТОДИ

    /** @return static[] */
    public static function all(PDO $pdo, string $table = ''): array
    {
        $model = new static($pdo, $table);

        $stmt = $pdo->query(
            "SELECT * FROM `{$model->table}` ORDER BY id"
        );

        $models = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $models[] = new static($pdo, $model->table, $row);
        }

        $stmt->closeCursor();
        return $models;
    }

    public static function find(
        PDO $pdo,
        int $id,
        string $table = ''
    ): ?static {
        $model = new static($pdo, $table);

        $stmt = $pdo->prepare(
            "SELECT * FROM `{$model->table}` WHERE id = ?"
        );

        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $row === false
            ? null
            : new static($pdo, $model->table, $row);
    }

    public static function create(
        PDO $pdo,
        array $data,
        string $table = ''
    ): ?static {
        $model = new static($pdo, $table);

        // Дозволяємо лише поля таблиці, крім id
        $fields = array_diff($model->fields, ['id']);

        $data = array_intersect_key(
            $data,
            array_flip($fields)
        );

        if ($data === []) {
            throw new InvalidArgumentException('Немає даних для додавання.');
        }

        $columns = '`' . implode('`, `', array_keys($data)) . '`';
        $markers = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$model->table}`
                ($columns) VALUES ($markers)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));

        $id = (int) $pdo->lastInsertId();
        $stmt->closeCursor();

        return static::find($pdo, $id, $model->table);
    }

    // МЕТОДИ ОБ'ЄКТА

    public function update(array $data): bool
    {
        // Не дозволяємо змінювати id
        $fields = array_diff($this->fields, ['id']);

        $data = array_intersect_key(
            $data,
            array_flip($fields)
        );

        if ($this->id === null || $data === []) {
            return false;
        }

        $set = [];

        foreach (array_keys($data) as $field) {
            $set[] = "`$field` = ?";
        }

        $sql = "UPDATE `{$this->table}` SET "
             . implode(', ', $set)
             . " WHERE id = ?";

        $values = array_values($data);
        $values[] = $this->id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        $stmt->closeCursor();

        foreach ($data as $name => $value) {
            $this->attributes[$name] = $value;
        }

        return true;
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "DELETE FROM `{$this->table}` WHERE id = ?"
        );

        $stmt->execute([$this->id]);

        $deleted = $stmt->rowCount() === 1;
        $stmt->closeCursor();

        if ($deleted) {
            $this->attributes['id'] = null;
        }

        return $deleted;
    }
}
