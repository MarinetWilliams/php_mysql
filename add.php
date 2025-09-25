<?php
require_once 'config.php';
require_once 'helpers.php';

$errors = [];
$name = $email = $phone = $notes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Неверный CSRF токен');
    }

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if ($name === '') $errors[] = 'Введите имя';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный email';
    if (strlen($name) > 255) $errors[] = 'Имя слишком длинное (макс 255 символов)';
    if (strlen($email) > 255) $errors[] = 'Email слишком длинный (макс 255 символов)';
    if (strlen($phone) > 50) $errors[] = 'Телефон слишком длинный (макс 50 символов)';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO clients (title, email, phone, description) VALUES (:name, :email, :phone, :notes)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':notes' => $notes,
        ]);
        set_flash('Клиент успешно добавлен.');
        redirect('index.php');
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить клиента</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{ --milk:#f7f5ef; --accent:#2f8f4a; --muted:#6c757d; }
    body{ background:var(--milk); padding:36px; font-family:Inter,system-ui, -apple-system, "Segoe UI", Roboto; }
    .card{ max-width:760px; margin:20px auto; border-radius:12px; box-shadow:0 10px 30px rgba(16,16,16,0.06); border:none; }
    .hero { max-width:760px; margin: 6px auto 0; text-align:center; }
    .hero h1{ font-size:20px; margin-bottom:6px; color:#222; }
    .hero p{ color:var(--muted); margin:0 0 14px; }
    .btn-accent{ background:var(--accent); border-color:var(--accent); color:#fff; }
    .small-muted{ color:var(--muted); font-size:13px; }
  </style>
</head>
<body>
  <div class="hero">
    <h1>Добавить клиента</h1>
    <p class="small-muted">Заполните форму, чтобы добавить нового клиента</p>
  </div>

  <div class="card p-4">
    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul><?php foreach ($errors as $err) echo '<li>' . e($err) . '</li>'; ?></ul>
      </div>
    <?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
      <div class="mb-3">
        <label class="form-label">Имя</label>
        <input type="text" name="name" class="form-control" value="<?= e($name) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= e($email) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Телефон</label>
        <input type="text" name="phone" class="form-control" value="<?= e($phone) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Примечания</label>
        <textarea name="notes" class="form-control" rows="4"><?= e($notes) ?></textarea>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-accent" type="submit">Добавить</button>
        <a href="index.php" class="btn btn-outline-secondary">Назад</a>
      </div>
    </form>
  </div>
</body>
</html>
