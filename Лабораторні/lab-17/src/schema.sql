-- Виконати один раз у порожній навчальній базі lab17.
CREATE TABLE `groups` (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    group_id INT UNSIGNED NULL,
    FOREIGN KEY (group_id) REFERENCES `groups` (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `groups` (name) VALUES ('Група А'), ('Група Б');
INSERT INTO students (name, group_id) VALUES
    ('Студент 1', 1), ('Студент 2', 1), ('Студент 3', NULL);
