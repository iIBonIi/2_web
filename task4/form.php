<?php
require 'config.php';

$formValues = json_decode($_COOKIE[COOKIE_VALUES] ?? '{}', true);
$formErrors = json_decode($_COOKIE[COOKIE_ERRORS] ?? '{}', true);

setcookie(COOKIE_ERRORS, '', time() - 3600, '/');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Форма с валидацией</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Анкета разработчика</h1>
        
        <?php if (!empty($formErrors['general'])): ?>
            <div class="error-message"><?= $formErrors['general'] ?></div>
        <?php endif; ?>
        
        <form method="POST" action="submit.php">
            <div class="form-group <?= isset($formErrors['full_name']) ? 'has-error' : '' ?>">
                <label>ФИО:</label>
                <input type="text" name="full_name" 
                       value="<?= htmlspecialchars($formValues['full_name'] ?? '') ?>">
                <?php if (isset($formErrors['full_name'])): ?>
                    <div class="error-text"><?= $formErrors['full_name'] ?></div>
                <?php endif; ?>
            </div>
            
            
            <button type="submit">Отправить</button>
        </form>
    </div>
</body>
</html>