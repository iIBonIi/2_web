
<?php
require 'config.php';

function get_cookie_or_empty($key) {
    return isset($_COOKIE[$key]) ? htmlspecialchars($_COOKIE[$key]) : '';
}

if (isset($_SESSION['user'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch();
    echo "<h2>Добро пожаловать, " . htmlspecialchars($user['login']) . "</h2>";
    echo "<form action='form.php' method='POST'>
            Имя: <input type='text' name='name' value='".htmlspecialchars($user['name'])."'><br>
            Email: <input type='email' name='email' value='".htmlspecialchars($user['email'])."'><br>
            Сообщение: <textarea name='message'>".htmlspecialchars($user['message'])."</textarea><br>
            <button type='submit'>Сохранить</button>
          </form>
          <a href='logout.php'>Выйти</a>";
} else {
    echo "<form action='form.php' method='POST'>
            Имя: <input type='text' name='name' value='".get_cookie_or_empty("name")."'><br>
            Email: <input type='email' name='email' value='".get_cookie_or_empty("email")."'><br>
            Сообщение: <textarea name='message'>".get_cookie_or_empty("message")."</textarea><br>
            <button type='submit'>Отправить</button>
          </form>
          <form action='login.php' method='POST'>
            <h3>Вход</h3>
            Логин: <input type='text' name='login'><br>
            Пароль: <input type='password' name='password'><br>
            <button type='submit'>Войти</button>
          </form>";
}
?>
