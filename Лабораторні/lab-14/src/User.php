<?php
class User
{
    private $pdo;
    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Отримання всіх користувачів з таблиці "users"
    public static function all(PDO $pdo)
    {
        $sql = "SELECT * FROM users";
        $stmt = $pdo->query($sql);

        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($pdo);
            $user->id = $row['id'];
            $user->username = $row['username'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $users[] = $user;
        }

        return $users;
    }

    // Знайти користувача за його ID
    public static function find(PDO $pdo, $id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_OBJ);
            if ($res) {
                $user = new User($pdo);
                $user->id = $res->id;
                $user->username = $res->username;
                $user->email = $res->email;
                $user->password = $res->password;
                return $user;
            }
        }

        return null;
    }

    // Додавання нового користувача
    public static function create($pdo, $username, $email, $password)
    {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));

        if ($stmt->execute()) {
            $user = new User($pdo);
            $user->id = $pdo->lastInsertId();
            $user->username = $username;
            $user->email = $email;
            $user->password = $password;
            return $user;
        } else {
            return null;
        }
    }

    // Оновлення існуючого користувача
    public function update()
    {
        $sql = "UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Видалення користувача
    public static function delete(PDO $pdo, User &$user)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $user->id);

        if ($stmt->execute()) {
            unset($user);
            return true;
        } else {
            return false;
        }
    }
}