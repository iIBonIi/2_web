<?php
define('DB_DSN', 'mysql:host=localhost;dbname=u68916;charset=utf8mb4');
define('DB_USER', 'u68916');
define('DB_PASS', '3541405');
function db(): PDO {
  static $pdo;
  if (!$pdo) {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }
  return $pdo;
}
