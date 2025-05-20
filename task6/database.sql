
CREATE DATABASE IF NOT EXISTS web_form;
USE web_form;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255),
    message TEXT
);

CREATE TABLE IF NOT EXISTS languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS user_languages (
    user_id INT,
    language_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

INSERT INTO admins (login, password_hash)
VALUES ('admin', '$2y$10$e0NRX6FzjczNVp2k8rcK5OZwPAIZyyWqB8v3HLmAS6e.0eRZTxCA2'); -- пароль: adminpass

INSERT IGNORE INTO languages (name) VALUES ('PHP'), ('Python'), ('JavaScript'), ('C++'), ('Java');
