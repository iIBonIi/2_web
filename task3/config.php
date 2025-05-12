<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u68916');
define('DB_USER', 'u68916');
define('DB_PASS', '3541405');

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>

<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'u68916');
define('DB_USER', 'u68916');
define('DB_PASS', '3541405');

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>