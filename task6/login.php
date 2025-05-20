
<?php
require 'config.php';

$login = $_POST['login'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
$stmt->execute([$login]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user'] = $login;
    header("Location: index.php");
} else {
    echo "Неверный логин или пароль";
}
?>
