<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $s = db()->prepare('SELECT pass_hash, app_id FROM users WHERE login = ?');
  $s->execute([$_POST['login']]);
  $u = $s->fetch();
  if ($u && password_verify($_POST['pass'], $u['pass_hash'])) {
    $_SESSION['uid'] = $u['app_id'];
    header('Location: index.php');
    exit;
  }
  $err = 'Неверный логин/пароль';
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Вход</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if (!empty($err)): ?>
    <div class="error"><?= $err ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="form-group">
      <input name="login" placeholder="login">
    </div>
    <div class="form-group">
      <input name="pass" type="password" placeholder="password">
    </div>
    <button type="submit">Войти</button>
  </form>
</body>
</html>
