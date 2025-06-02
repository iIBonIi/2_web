CREATE DATABASE IF NOT EXISTS u68916 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE u68916;

CREATE TABLE IF NOT EXISTS applications (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  birthdate DATE NOT NULL,
  gender ENUM('male','female') NOT NULL,
  biography TEXT,
  agreement TINYINT(1) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS programming_languages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO programming_languages (name) VALUES
('Pascal'),('C'),('C++'),('JavaScript'),('PHP'),
('Python'),('Java'),('Haskell'),('Clojure'),
('Prolog'),('Scala'),('Go');

CREATE TABLE IF NOT EXISTS application_languages (
  application_id INT UNSIGNED NOT NULL,
  language_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (application_id, language_id),
  FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
  FOREIGN KEY (language_id) REFERENCES programming_languages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  login VARCHAR(50) NOT NULL UNIQUE,
  pass_hash VARCHAR(255) NOT NULL,
  app_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (app_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
