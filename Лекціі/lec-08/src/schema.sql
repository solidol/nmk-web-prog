-- Виконати один раз у власній навчальній базі, вибраній перед імпортом.
-- Наявні таблиці не видаляються; за конфлікту імен оберіть іншу навчальну базу.
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    stock INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (title, stock) VALUES
    ('Зошит', 10),
    ('Папка', 5);
