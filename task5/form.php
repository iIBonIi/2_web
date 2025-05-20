
<?php
require 'config.php';

function validate($name, $email, $message) {
    if (empty($name) || strlen($name) > 100) return "Имя должно быть непустым и до 100 символов.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Неверный формат email.";
    if (strlen($message) > 1000) return "Сообщение слишком длинное (макс. 1000 символов).";
    return "";
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

$error = validate($name, $email, $message);
if ($error) {
    echo "Ошибка: $error<br><a href='index.php'>Назад</a>";
    exit;
}

if (!isset($_SESSION['user'])) {
    setcookie("name", $name, time() + 3600);
    setcookie("email", $email, time() + 3600);
    setcookie("message", $message, time() + 3600);

    $login = uniqid("user");
    $password = bin2hex(random_bytes(4));
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, name, email, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$login, $password_hash, $name, $email, $message]);

    echo "Ваш логин: $login<br>Ваш пароль: $password<br>Сохраните их!";
} else {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, message = ? WHERE login = ?");
    $stmt->execute([$name, $email, $message, $_SESSION['user']]);
    echo "Данные обновлены.";
}
?>
<br><a href='index.php'>Назад</a>
