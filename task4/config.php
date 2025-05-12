<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'u68916');
define('DB_USER', 'u68916');
define('DB_PASS', '3541405');

define('COOKIE_ERRORS', 'form_errors');
define('COOKIE_VALUES', 'form_values');
define('COOKIE_LIFETIME', time() + 60*60*24*365);

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

function validateField($value, $pattern, $errorMessage) {
    if (!preg_match($pattern, $value)) {
        return $errorMessage;
    }
    return null;
}
?>