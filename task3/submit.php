<?php
require 'config.php';

$errors = [];

if (empty($_POST['full_name']) || !preg_match('/^[А-ЯЁа-яёA-Za-z\s\-]{2,150}$/u', $_POST['full_name'])) {
    $errors[] = "ФИО должно содержать только буквы и быть от 2 до 150 символов";
}

if (empty($_POST['phone']) || !preg_match('/^\+?[0-9\s\-]{6,20}$/', $_POST['phone'])) {
    $errors[] = "Некорректный формат телефона";
}

if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Укажите корректный email";
}

if (empty($_POST['birthdate'])) {
    $errors[] = "Укажите дату рождения";
}

if (empty($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female'])) {
    $errors[] = "Укажите пол";
}

if (empty($_POST['languages']) || !is_array($_POST['languages'])) {
    $errors[] = "Выберите хотя бы один язык программирования";
}

if (empty($_POST['biography']) || strlen($_POST['biography']) > 1000) {
    $errors[] = "Биография должна быть не длиннее 1000 символов";
}

if (!isset($_POST['agreement'])) {
    $errors[] = "Необходимо согласиться с условиями";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: form.php");
    exit;
}

try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("INSERT INTO applications 
        (full_name, phone, email, birthdate, gender, biography, agreement) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        htmlspecialchars($_POST['full_name']),
        $_POST['phone'],
        filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
        $_POST['birthdate'],
        $_POST['gender'],
        htmlspecialchars($_POST['biography']),
        isset($_POST['agreement']) ? 1 : 0
    ]);
    
    $application_id = $pdo->lastInsertId();
    
    $stmt = $pdo->prepare("INSERT INTO application_languages 
        (application_id, language_id) VALUES (?, ?)");
    
    foreach ($_POST['languages'] as $lang_id) {
        $stmt->execute([$application_id, (int)$lang_id]);
    }
    
    $pdo->commit();
    
    header("Location: success.php?id=".$application_id);
    exit;
    
} catch (PDOException $e) {
    $pdo->rollBack();
    
    error_log("Database error: " . $e->getMessage());
    
    $_SESSION['errors'] = ["Произошла ошибка при сохранении данных. Пожалуйста, попробуйте позже."];
    $_SESSION['form_data'] = $_POST;
    header("Location: form.php");
    exit;
}
?>