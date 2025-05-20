
<?php
require 'config.php';

// HTTP Basic Auth через БД
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Требуется авторизация';
    exit;
}

$login = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

$stmt = $pdo->prepare("SELECT * FROM admins WHERE login = ?");
$stmt->execute([$login]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Неверные данные администратора';
    exit;
}

echo "<h2>Панель администратора</h2>";

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    echo "Пользователь #$id удалён.<br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, message=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['message'],
        $_POST['id']
    ]);
    echo "Пользователь #{$_POST['id']} обновлён.<br>";
}

// Вывод всех пользователей
echo "<h3>Все пользователи:</h3>";

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "<form method='POST'>
        ID: {$user['id']}<br>
        Имя: <input name='name' value='".htmlspecialchars($user['name'])."'><br>
        Email: <input name='email' value='".htmlspecialchars($user['email'])."'><br>
        Сообщение: <textarea name='message'>".htmlspecialchars($user['message'])."</textarea><br>
        <input type='hidden' name='id' value='{$user['id']}'>
        <input type='submit' value='Сохранить'>
        <a href='?delete={$user['id']}' onclick='return confirm("Удалить?")'>Удалить</a>
    </form><hr>";
}

// Статистика по языкам
echo "<h3>Статистика по языкам программирования:</h3>";
$sql = "SELECT l.name, COUNT(ul.user_id) AS count
        FROM languages l
        LEFT JOIN user_languages ul ON l.id = ul.language_id
        GROUP BY l.name";
$stmt = $pdo->query($sql);
foreach ($stmt as $row) {
    echo "<b>{$row['name']}</b>: {$row['count']} пользователей<br>";
}
?>
