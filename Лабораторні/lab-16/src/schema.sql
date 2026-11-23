
-- Виконати один раз у власній порожній навчальній базі lab16.

-- Таблиця товарів
CREATE TABLE products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    price DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO products (title, price) VALUES
    ('Зошит', '25.50'),
    ('Папка', '40.00'),
    ('Набір ручок', '60.25');


-- Таблиця користувачів
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE KEY (username),
    UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Тестові користувачі без паролів для входу.
-- Порожній рядок тут не є хешем пароля.
INSERT INTO users (username, email, password) VALUES
    ('ivan', 'ivan@example.com', ''),
    ('maria', 'maria@example.com', ''),
    ('petro', 'petro@example.com', '');


-- Таблиця книг
CREATE TABLE books (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(100) NOT NULL,
    year SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO books (title, author, year) VALUES
    ('Кобзар', 'Тарас Шевченко', 1840),
    ('Лісова пісня', 'Леся Українка', 1911),
    ('Захар Беркут', 'Іван Франко', 1883),
    ('Тигролови', 'Іван Багряний', 1944),
    ('Місто', 'Валер’ян Підмогильний', 1928);
