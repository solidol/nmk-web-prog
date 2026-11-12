CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (name, category) VALUES
('Хліб', 'Випічка'),
('Молоко', 'Молочні продукти'),
('Булочка', 'Випічка');

SELECT id, name, category FROM products ORDER BY id;
