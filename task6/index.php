<?php
require_once 'db.php';
session_start();
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO applications(full_name,phone,email,birthdate,gender,biography,agreement) VALUES(?,?,?,?,?,?,?)');
    $stmt->execute([
        $_POST['full_name'] ?? '',
        $_POST['phone'] ?? '',
        $_POST['email'] ?? '',
        $_POST['birthdate'] ?? '',
        $_POST['gender'] ?? 'male',
        $_POST['biography'] ?? '',
        isset($_POST['agreement']) ? 1 : 0
    ]);
    $app_id = $pdo->lastInsertId();
    if (!empty($_POST['langs'])) {
        $ins = $pdo->prepare('INSERT INTO application_languages(application_id,language_id) VALUES(?,?)');
        foreach ($_POST['langs'] as $l) {
            $ins->execute([$app_id, (int)$l]);
        }
    }
    $success = true;
}
$langs = $pdo->query('SELECT id, name FROM programming_languages ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма заявки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Форма заявки</h1>
    <?php if ($success): ?>
        <div class="success">Заявка отправлена</div>
    <?php endif; ?>
    <form method="post">
        <table>
            <tr>
                <td>Имя</td>
                <td><input type="text" name="full_name" required></td>
            </tr>
            <tr>
                <td>Телефон</td>
                <td><input type="text" name="phone" required></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" required></td>
            </tr>
            <tr>
                <td>Дата рождения</td>
                <td><input type="date" name="birthdate" required></td>
            </tr>
            <tr>
                <td>Пол</td>
                <td>
                    <select name="gender">
                        <option value="male">male</option>
                        <option value="female">female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Биография</td>
                <td><textarea name="biography"></textarea></td>
            </tr>
            <tr>
                <td>Языки</td>
                <td>
                    <select name="langs[]" multiple>
                        <?php foreach ($langs as $l): ?>
                            <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Согласие</td>
                <td><input type="checkbox" name="agreement" value="1" required></td>
            </tr>
            <tr>
                <td colspan="2"><button type="submit">Отправить</button></td>
            </tr>
        </table>
    </form>
</body>
</html>
