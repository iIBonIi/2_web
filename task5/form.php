<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Анкета</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php foreach ($messages as $m): ?>
    <div class="error"><?= $m ?></div>
  <?php endforeach; ?>

  <form method="post">
    <div class="form-group">
      <label>ФИО:
        <input name="full_name" value="<?= e($values['full_name']) ?>">
      </label>
    </div>

    <div class="form-group">
      <label>Телефон:
        <input name="phone" value="<?= e($values['phone']) ?>">
      </label>
    </div>

    <div class="form-group">
      <label>Email:
        <input name="email" value="<?= e($values['email']) ?>">
      </label>
    </div>

    <div class="form-group">
      <label>Дата рождения:
        <input type="date" name="birthdate" value="<?= e($values['birthdate']) ?>">
      </label>
    </div>

    <div class="form-group">
      <label>Пол:
        <select name="gender">
          <option value="male"   <?= ($values['gender'] === 'male')   ? 'selected' : '' ?>>М</option>
          <option value="female" <?= ($values['gender'] === 'female') ? 'selected' : '' ?>>Ж</option>
        </select>
      </label>
    </div>

    <div class="form-group">
      <label>Биография:
        <textarea name="biography"><?= e($values['biography']) ?></textarea>
      </label>
    </div>

    <div class="form-group">
      <label>Языки программирования:</label>
      <div class="checkbox-group">
        <?php
        foreach (db()->query('SELECT id, name FROM programming_languages') as $lang) {
          $checked = in_array($lang['id'], $values['langs']) ? 'checked' : '';
          echo "<label><input type=\"checkbox\" name=\"langs[]\" value=\"{$lang['id']}\" {$checked}> {$lang['name']}</label>";
        }
        ?>
      </div>
    </div>

    <div class="form-group agreement">
      <input type="checkbox" name="agreement" value="1" <?= ($values['agreement'] ? 'checked' : '') ?>>
      <label>Соглашаюсь с обработкой данных</label>
    </div>

    <button type="submit">Сохранить</button>
  </form>
</body>
</html>
