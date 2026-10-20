-- Виконайте один раз у власній порожній навчальній базі lab11.
-- Не імпортуйте повторно поверх таблиці з виконаними завданнями.
CREATE TABLE products (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_code VARCHAR(20) NOT NULL,
    product_name VARCHAR(80) NOT NULL,
    product_desc VARCHAR(500) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_products_code (product_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (product_code, product_name, product_desc, price) VALUES
    ('PD1001', 'Зошит', 'Зошит для навчальних нотаток', '25.50'),
    ('PD1002', 'Папка', 'Папка для матеріалів курсу', '40.00'),
    ('PD1003', 'Набір ручок', 'Навчальний набір, 3 кольори', '60.25');
