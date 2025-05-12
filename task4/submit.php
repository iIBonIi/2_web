<?php
require 'config.php';

$patterns = [
    'full_name' => '/^[А-ЯЁа-яёA-Za-z\s\-]{2,150}$/u',
    'phone' => '/^\+?[0-9\s\-]{6,20}$/',
    'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
];

$errorMessages = [
    'full_name' => 'ФИО должно содержать только буквы и быть от 2 до 150 символов',
    'phone' => 'Телефон должен быть в формате +7 XXX XXX XX XX',
    'email' => 'Введите корректный email'
];

$errors = [];
$values = $_POST;

foreach ($patterns as $field => $pattern) {
    if (empty($_POST[$field]) || !preg_match($pattern, $_POST[$field])) {
        $errors[$field] = $errorMessages[$field];
    }
}

if (!empty($errors)) {
    setcookie(COOKIE_ERRORS, json_encode($errors), 0, '/');
    setcookie(COOKIE_VALUES, json_encode($values), 0, '/');
    
    header("Location: form.php");
    exit;
}

try { 
    
    setcookie(COOKIE_VALUES, json_encode($values), COOKIE_LIFETIME, '/');
    
    header("Location: success.php");
    exit;
    
} catch (PDOException $e) {
    $errors['general'] = "Ошибка сервера: " . $e->getMessage();
    setcookie(COOKIE_ERRORS, json_encode($errors), 0, '/');
    header("Location: form.php");
    exit;
}
?>