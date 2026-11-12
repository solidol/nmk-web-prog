-- Виконати один раз у власній порожній навчальній базі lab14.
-- Якщо таблиця users уже існує, використайте іншу базу; старі дані не видаляйте.
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL,
    email VARCHAR(254) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Вигадані навчальні записи. Хеш створено password_hash() для demo-pass.
INSERT INTO users (username, email, password) VALUES
    ('Студент DEMO', 'demo@example.test', '$2y$10$XCyoTD1yUEBqc1hM1gA4tenJtu8pSo7OpcWqBj52dUzD75A5RmNkm'),
    ('Олена DEMO', 'olena@example.test', '$2y$10$XCyoTD1yUEBqc1hM1gA4tenJtu8pSo7OpcWqBj52dUzD75A5RmNkm');
