<?php
require_once 'db.php';
header('Content-Type: text/html; charset=UTF-8');
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.1 401 Unauthorized');
    exit;
}
$q = $pdo->prepare('SELECT pass_hash FROM admin_users WHERE login = ?');
$q->execute([$_SERVER['PHP_AUTH_USER']]);
$hash = $q->fetchColumn();
if (!$hash || !password_verify($_SERVER['PHP_AUTH_PW'], $hash)) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.1 401 Unauthorized');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = (int)$_POST['delete'];
        $pdo->prepare('DELETE FROM application_languages WHERE application_id = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM applications WHERE id = ?')->execute([$id]);
    }
    if (isset($_POST['save'])) {
        $id = (int)$_POST['id'];
        $f = ['full_name', 'phone', 'email', 'birthdate', 'gender', 'biography', 'agreement'];
        $set = [];
        $v = [];
        foreach ($f as $k) {
            $set[] = "$k = ?";
            $v[] = $_POST[$k] ?? null;
        }
        $v[] = $id;
        $pdo->prepare('UPDATE applications SET ' . implode(', ', $set) . ' WHERE id = ?')->execute($v);
        $pdo->prepare('DELETE FROM application_languages WHERE application_id = ?')->execute([$id]);
        if (!empty($_POST['langs'])) {
            $ins = $pdo->prepare('INSERT INTO application_languages(application_id,language_id) VALUES(?,?)');
            foreach ($_POST['langs'] as $l) {
                $ins->execute([$id, (int)$l]);
            }
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$app = $pdo->query("
    SELECT
        a.*,
        GROUP_CONCAT(pl.name ORDER BY pl.name SEPARATOR ', ') AS langs
    FROM applications AS a
    LEFT JOIN application_languages AS al ON a.id = al.application_id
    LEFT JOIN programming_languages AS pl ON pl.id = al.language_id
    GROUP BY a.id
    ORDER BY a.id
")->fetchAll(PDO::FETCH_ASSOC);
$stat = $pdo->query("
    SELECT pl.name, COUNT(al.language_id) AS cnt
    FROM programming_languages AS pl
    LEFT JOIN application_languages AS al ON pl.id = al.language_id
    GROUP BY pl.id
    ORDER BY cnt DESC, pl.name
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Заявки</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Дата рожд.</th>
            <th>Пол</th>
            <th>Био</th>
            <th>Согласие</th>
            <th>Языки</th>
            <th>Ред.</th>
            <th>Удал.</th>
        </tr>
        <?php foreach ($app as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id'], ENT_QUOTES) ?></td>
                <?php if ((int)($_GET['edit'] ?? 0) === $r['id']): ?>
                    <form method="post" class="inline">
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <td><input type="text" name="full_name" value="<?= htmlspecialchars($r['full_name'], ENT_QUOTES) ?>"></td>
                        <td><input type="text" name="phone" value="<?= htmlspecialchars($r['phone'], ENT_QUOTES) ?>"></td>
                        <td><input type="text" name="email" value="<?= htmlspecialchars($r['email'], ENT_QUOTES) ?>"></td>
                        <td><input type="date" name="birthdate" value="<?= htmlspecialchars($r['birthdate'], ENT_QUOTES) ?>"></td>
                        <td>
                            <select name="gender">
                                <option value="male" <?= $r['gender'] === 'male' ? 'selected' : '' ?>>male</option>
                                <option value="female" <?= $r['gender'] === 'female' ? 'selected' : '' ?>>female</option>
                            </select>
                        </td>
                        <td><textarea name="biography"><?= htmlspecialchars($r['biography'], ENT_QUOTES) ?></textarea></td>
                        <td>
                            <select name="agreement">
                                <option value="1" <?= $r['agreement'] ? 'selected' : '' ?>>yes</option>
                                <option value="0" <?= !$r['agreement'] ? 'selected' : '' ?>>no</option>
                            </select>
                        </td>
                        <td>
                            <select name="langs[]" multiple>
                                <?php
                                $all = $pdo->query('SELECT id, name FROM programming_languages ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
                                $sel = explode(', ', $r['langs'] ?? '');
                                foreach ($all as $pl): ?>
                                    <option value="<?= $pl['id'] ?>" <?= in_array($pl['name'], $sel) ? 'selected' : '' ?>>
                                        <?= $pl['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td colspan="2"><button type="submit" name="save" value="1">Сохранить</button></td>
                    </form>
                <?php else: ?>
                    <td><?= htmlspecialchars($r['full_name'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($r['phone'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($r['email'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($r['birthdate'], ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($r['gender'], ENT_QUOTES) ?></td>
                    <td><?= nl2br(htmlspecialchars($r['biography'], ENT_QUOTES)) ?></td>
                    <td><?= $r['agreement'] ? 'yes' : 'no' ?></td>
                    <td><?= htmlspecialchars($r['langs'], ENT_QUOTES) ?></td>
                    <td><a class="edit-link" href="?edit=<?= $r['id'] ?>">✏️</a></td>
                    <td>
                        <form method="post" class="inline" onsubmit="return confirm('Удалить?');">
                            <button type="submit" name="delete" value="<?= $r['id'] ?>">🗑</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Статистика по языкам</h2>
    <table>
        <tr>
            <th>Язык</th>
            <th>Пользователей</th>
        </tr>
        <?php foreach ($stat as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['name'], ENT_QUOTES) ?></td>
                <td><?= $s['cnt'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
