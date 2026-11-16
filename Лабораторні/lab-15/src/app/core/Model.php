<?php
class Model
{
    protected PDO $pdo;
    protected string $table;
    protected array $fields;
    protected array $attributes;

    public function __construct(PDO $pdo, string $table, array $fields, array $data = [])
    {
        // Імена SQL не можна передати через ?. Беремо прості ідентифікатори з коду.
        foreach (array_merge([$table], $fields) as $name) {
            if (!preg_match('/\A[a-zA-Z_][a-zA-Z0-9_]*\z/', $name)) {
                throw new InvalidArgumentException('Некоректне ім’я таблиці або поля.');
            }
        }
        $this->pdo = $pdo;
        $this->table = $table;
        $this->fields = array_values(array_diff($fields, ['id']));
        $this->attributes = ['id' => null] + array_fill_keys($this->fields, null);
        foreach ($data as $name => $value) {
            if (array_key_exists($name, $this->attributes)) {
                $this->attributes[$name] = $value;
            }
        }
        if ($this->attributes['id'] !== null) {
            $this->attributes['id'] = (int) $this->attributes['id'];
        }
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        if (in_array($name, $this->fields, true)) {
            $this->attributes[$name] = $value;
        }
    }

    /** @return static[] */
    public function all(): array
    {
        $columns = '`' . implode('`, `', array_keys($this->attributes)) . '`';
        $stmt = $this->pdo->query("SELECT $columns FROM `{$this->table}` ORDER BY id");
        $models = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $models[] = new static($this->pdo, $this->table, $this->fields, $row);
        }
        $stmt->closeCursor();
        return $models;
    }

    public function find(int $id): ?static
    {
        $columns = '`' . implode('`, `', array_keys($this->attributes)) . '`';
        $stmt = $this->pdo->prepare("SELECT $columns FROM `{$this->table}` WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row === false ? null : new static($this->pdo, $this->table, $this->fields, $row);
    }

    public function create(array $data): ?static
    {
        // Лише явно дозволені поля; id і сторонні ключі не потрапляють до INSERT.
        $data = array_intersect_key($data, array_flip($this->fields));
        if ($data === []) {
            throw new InvalidArgumentException('Не передано даних для додавання.');
        }
        $columns = '`' . implode('`, `', array_keys($data)) . '`';
        $markers = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->pdo->prepare("INSERT INTO `{$this->table}` ($columns) VALUES ($markers)");
        $stmt->execute(array_values($data));
        $id = (int) $this->pdo->lastInsertId();
        $stmt->closeCursor();
        return $this->find($id);
    }

    public function update(array $data): bool
    {
        $data = array_intersect_key($data, array_flip($this->fields));
        if ($this->id === null || $data === []) {
            return false;
        }
        $set = [];
        foreach (array_keys($data) as $field) {
            $set[] = "`$field` = ?";
        }
        $sql = "UPDATE `{$this->table}` SET " . implode(', ', $set) . ' WHERE id = ?';
        $values = array_values($data);
        $values[] = $this->id;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        $stmt->closeCursor();
        foreach ($data as $name => $value) {
            $this->attributes[$name] = $value;
        }
        // true означає виконання SQL; за потреби стан уточнюють через find().
        return true;
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }
        $stmt = $this->pdo->prepare("DELETE FROM `{$this->table}` WHERE id = :id");
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $deleted = $stmt->rowCount() === 1;
        $stmt->closeCursor();
        if ($deleted) {
            $this->attributes['id'] = null;
        }
        return $deleted;
    }
}
