<?php
require 'config.php';

if (empty($_GET['id'])) {
    header("Location: form.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->execute([$_GET['id']]);
$application = $stmt->fetch();

if (!$application) {
    die("Анкета не найдена");
}

$langs = $pdo->prepare("SELECT pl.name FROM application_languages al 
    JOIN programming_languages pl ON al.language_id = pl.id 
    WHERE al.application_id = ?");
$langs->execute([$_GET['id']]);
$languages = $langs->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Успешная отправка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Данные успешно сохранены!</h1>
        
        <div class="info-block">
            <h2>Ваши данные:</h2>
            <p><strong>ФИО:</strong> <?= htmlspecialchars($application['full_name']) ?></p>
            <p><strong>Телефон:</strong> <?= htmlspecialchars($application['phone']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($application['email']) ?></p>
            <p><strong>Дата рождения:</strong> <?= $application['birthdate'] ?></p>
            <p><strong>Пол:</strong> <?= $application['gender'] == 'male' ? 'Мужской' : 'Женский' ?></p>
            
            <h3>Выбранные языки программирования:</h3>
            <ul>
                <?php foreach ($languages as $lang): ?>
                    <li><?= htmlspecialchars($lang) ?></li>
                <?php endforeach; ?>
            </ul>
            
            <p><strong>Биография:</strong></p>
            <div class="bio-text"><?= nl2br(htmlspecialchars($application['biography'])) ?></div>
            
            <a href="form.php" class="back-link">Заполнить новую анкету</a>
        </div>
    </div>
</body>
</html>