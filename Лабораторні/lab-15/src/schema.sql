-- Виконати один раз у власній порожній навчальній базі lab15.
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    price DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (title, price) VALUES
    ('Зошит', '25.50'),
    ('Папка', '40.00'),
    ('Набір ручок', '60.25');
