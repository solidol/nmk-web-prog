<?php

class Model
{
    protected static $pdo = null;
    protected static $table = null;

    public function __construct(PDO $pdo, $table, $data = null)
    {
        static::$pdo = $pdo;

        if (!static::$table) {
            static::$table = $table;
        }

        // Завантажуємо колонки таблиці → створюємо властивості
        $sql = "SHOW COLUMNS FROM `" . static::$table . "`";
        $stmt = $pdo->query($sql);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $column) {
            $fn = $column['Field'];
            $this->$fn = null;       // ← властивість напряму
        }

        // Якщо передано дані — встановлюємо
        if ($data) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    protected static function init(PDO $pdo, $table)
    {
        if (!static::$pdo) static::$pdo = $pdo;
        if (!static::$table) static::$table = $table;
    }

    protected static function createInstance(PDO $pdo, $table)
    {
        return new static($pdo, $table);
    }

    public static function all(PDO $pdo, $table = null)
    {
        static::init($pdo, $table);

        $sql = "SELECT * FROM `" . static::$table . "`";
        $stmt = static::$pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        foreach ($rows as $row) {
            $model = static::createInstance(static::$pdo, static::$table);
            foreach ($row as $key => $value) {
                $model->$key = $value;
            }
            $models[] = $model;
        }

        return $models;
    }

    public static function find(PDO $pdo, $id, $table = null)
    {
        static::init($pdo, $table);

        $sql = "SELECT * FROM `" . static::$table . "` WHERE id = :id LIMIT 1";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $model = static::createInstance(static::$pdo, static::$table);
        foreach ($row as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    public static function insert(PDO $pdo, $data, $table = null)
    {
        static::init($pdo, $table);

        $columns = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO `" . static::$table . "` ($columns) VALUES ($placeholders)";
        $stmt = static::$pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function update($data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "`$key` = :$key";
        }
        $set = implode(', ', $set);

        $sql = "UPDATE `" . static::$table . "` SET $set WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);

        $stmt->bindValue(':id', $this->id);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function delete()
    {
        $sql = "DELETE FROM `" . static::$table . "` WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindValue(':id', $this->id);

        return $stmt->execute();
    }
}
