<?php
class Model
{
    static $pdo = null;
    static $table = null;

    public $columns = null;
    public function __construct(PDO $pdo, $table, $data = null)
    {
        static::$pdo = $pdo;
        if (!static::$table)
            static::$table = $table;
        $this->columns = (object) [];

        $sql = "SHOW COLUMNS FROM " . static::$table;
        $stmt = $pdo->query($sql);

        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $column) {
            $fn = $column['Field'];
            $this->columns->$fn = null;
        }
        if ($data) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    protected static function createInstance(PDO $pdo, $table) {
        return new static($pdo, $table);
    }
    public static function all(PDO $pdo, $table = null)
    {
        if (!static::$pdo)
            static::$pdo = $pdo;
        if (!static::$table)
            static::$table = $table;
        $sql = "SELECT * FROM " . static::$table;
        $stmt = static::$pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $models = [];
        foreach ($rows as $row) {
            $model = static::createInstance(static::$pdo, static::$table);
            foreach ($row as $key => $value) {
                $model->columns->$key = $value;
            }
            $models[] = $model;
        }
        return $models;
    }

    public static function find(PDO $pdo, $id, $table = null)
    {
        if (!static::$pdo)
            static::$pdo = $pdo;
        if (!static::$table)
            static::$table = $table;
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $model = static::createInstance(static::$pdo, static::$table);
            foreach ($stmt->fetch(PDO::FETCH_ASSOC) as $key => $value) {
                $model->columns->$key = $value;
            }
            return $model;
        }
        return null;
    }

    public static function insert(PDO $pdo, $data, $table = null)
    {
        if (!static::$pdo)
            static::$pdo = $pdo;
        if (!static::$table)
            static::$table = $table;
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO ".static::$table." ($columns) VALUES ($values)";
        $stmt = static::$pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        return $stmt->execute();
    }

    public function update($data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(', ', $set);

        $sql = "UPDATE $this->table SET $set WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $this->columns->id);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        return $stmt->execute();
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = static::$pdo->prepare($sql);
        $stmt->bindParam(':id', $this->columns->id);
        return $stmt->execute();
    }


}
