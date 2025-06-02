<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'db.php';

function redirect() { header('Location: ' . $_SERVER['PHP_SELF']); exit; }
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$messages = [];
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $values = [
    'full_name' => '',
    'phone'     => '',
    'email'     => '',
    'birthdate' => '',
    'gender'    => '',
    'biography' => '',
    'agreement' => '',
    'langs'     => []
  ];

  if (!empty($_SESSION['uid'])) {
    $stmt = db()->prepare('SELECT * FROM applications WHERE id = ?');
    $stmt->execute([$_SESSION['uid']]);
    $values = $stmt->fetch() ?: $values;

    $langs = db()->prepare(
      'SELECT language_id FROM application_languages WHERE application_id = ?'
    );
    $langs->execute([$_SESSION['uid']]);
    $values['langs'] = $langs->fetchAll(PDO::FETCH_COLUMN);
  }

  include 'form.php';
  exit;
}

$errors = [];

foreach (['full_name', 'phone', 'email', 'birthdate', 'gender'] as $field) {
  if (empty($_POST[$field])) {
    $errors[] = "Поле «$field» обязательно.";
  }
}

if (empty($_POST['agreement'])) {
  $errors[] = 'Необходимо согласие.';
}

if (!empty($errors)) {
  $messages = $errors;
  include 'form.php';
  exit;
}

$app = [
  $_POST['full_name'],
  $_POST['phone'],
  $_POST['email'],
  $_POST['birthdate'],
  $_POST['gender'],
  $_POST['biography'] ?? '',
  1
];

if (!empty($_SESSION['uid'])) {
  $sql = '
    UPDATE applications
    SET full_name = ?, phone = ?, email = ?, birthdate = ?, gender = ?, biography = ?, agreement = ?
    WHERE id = ?
  ';
  db()->prepare($sql)->execute(array_merge($app, [$_SESSION['uid']]));

  db()->prepare('DELETE FROM application_languages WHERE application_id = ?')
       ->execute([$_SESSION['uid']]);
  $appId = $_SESSION['uid'];
  $messages[] = 'Данные обновлены.';
} else {
  $sql = '
    INSERT INTO applications
      (full_name, phone, email, birthdate, gender, biography, agreement)
    VALUES (?, ?, ?, ?, ?, ?, ?)
  ';
  db()->prepare($sql)->execute($app);
  $appId = db()->lastInsertId();
  $_SESSION['uid'] = $appId;

  $login = 'user' . $appId;
  $pass  = bin2hex(random_bytes(4));
  $hash  = password_hash($pass, PASSWORD_DEFAULT);
  db()->prepare('INSERT INTO users(login, pass_hash, app_id) VALUES (?, ?, ?)')
       ->execute([$login, $hash, $appId]);

  $messages[] = "Сохранено. Ваш логин: <b>$login</b>, пароль: <b>$pass</b>";
}

$langIds = array_map('intval', $_POST['langs'] ?? []);
$stmt = db()->prepare('INSERT INTO application_languages VALUES (?, ?)');
foreach ($langIds as $langId) {
  $stmt->execute([$appId, $langId]);
}

redirect();
