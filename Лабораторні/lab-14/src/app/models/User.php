<?php
class User
{
    private PDO $pdo;
    public ?int $id = null;
    public string $username = '';
    public string $email = '';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return User[] */
    public static function all(PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT id, username, email FROM users ORDER BY id');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($pdo);
            $user->id = (int) $row['id'];
            $user->username = $row['username'];
            $user->email = $row['email'];
            $users[] = $user;
        }
        $stmt->closeCursor();
        return $users;
    }

    public static function find(PDO $pdo, int $id): ?User
    {
        $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if ($row === false) {
            return null;
        }
        $user = new User($pdo);
        $user->id = (int) $row['id'];
        $user->username = $row['username'];
        $user->email = $row['email'];
        return $user;
    }

    public static function create(PDO $pdo, string $username, string $email, string $password): User
    {
        $username = trim($username);
        $email = trim($email);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hash]);
        $id = (int) $pdo->lastInsertId();
        $stmt->closeCursor();

        $user = new User($pdo);
        $user->id = $id;
        $user->username = $username;
        $user->email = $email;
        return $user;
    }

    public function update(): bool
    {
        $this->username = trim($this->username);
        $this->email = trim($this->email);
        // Зміна профілю не змінює пароль і його хеш.
        $stmt = $this->pdo->prepare('UPDATE users SET username = :username, email = :email WHERE id = :id');
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        // true означає виконання запиту, а не обов’язково зміну рядка.
        return true;
    }

    public static function delete(PDO $pdo, User $user): bool
    {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindValue(':id', $user->id, PDO::PARAM_INT);
        $stmt->execute();
        $deleted = $stmt->rowCount() === 1;
        $stmt->closeCursor();
        if ($deleted) {
            $user->id = null;
        }
        return $deleted;
    }
}
