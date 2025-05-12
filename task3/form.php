<?php
require 'config.php';

$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['errors'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Анкета разработчика</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Анкета разработчика</h1>
        
        <?php foreach ($errors as $error): ?>
            <div class="error"><?= $error ?></div>
        <?php endforeach; ?>
        
        <form method="POST" action="submit.php">
            <div class="form-group">
                <label>ФИО:</label>
                <input type="text" name="full_name" required 
                       value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Телефон:</label>
                <input type="tel" name="phone" required 
                       value="<?= htmlspecialchars($formData['phone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required 
                       value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Дата рождения:</label>
                <input type="date" name="birthdate" required 
                       value="<?= htmlspecialchars($formData['birthdate'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Пол:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="gender" value="male" required
                            <?= isset($formData['gender']) && $formData['gender'] == 'male' ? 'checked' : '' ?>> Мужской
                    </label>
                    <label>
                        <input type="radio" name="gender" value="female"
                            <?= isset($formData['gender']) && $formData['gender'] == 'female' ? 'checked' : '' ?>> Женский
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Любимые языки программирования:</label>
                <select name="languages[]" multiple required class="languages-select">
                    <?php
                    $languages = $pdo->query("SELECT * FROM programming_languages");
                    $selectedLangs = $formData['languages'] ?? [];
                    foreach ($languages as $lang) {
                        $selected = in_array($lang['id'], $selectedLangs) ? 'selected' : '';
                        echo "<option value='{$lang['id']}' $selected>{$lang['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Биография:</label>
                <textarea name="biography" rows="4"><?= htmlspecialchars($formData['biography'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="agreement" required
                        <?= isset($formData['agreement']) ? 'checked' : '' ?>> 
                    Согласен с обработкой данных
                </label>
            </div>
            
            <button type="submit" class="submit-btn">Сохранить</button>
        </form>
    </div>
</body>
</html>