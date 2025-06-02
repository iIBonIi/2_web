CREATE TABLE admin_users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  login VARCHAR(50) NOT NULL UNIQUE,
  pass_hash VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin_users (login, pass_hash)
VALUES ('admin', '$2y$10$0MOL4hzGp0sIUCDyIeaucesUp8U37g7zPc6VgrPkNM9hsdzUWiK3a');
