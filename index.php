<?php
require_once 'config.php';
require_once 'helpers.php';

$stmt = $pdo->query('SELECT * FROM clients ORDER BY created_at DESC');
$clients = $stmt->fetchAll();
$flash = get_flash();
?>
<<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>База клиентов</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{
      --milk: #f7f5ef;
      --accent: #2f8f4a;
      --accent-weak: rgba(47,143,74,0.08);
      --muted: #6c757d;
    }
    body {
      background: var(--milk);
      color: #222;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      padding: 36px 12px;
    }
    .hero {
      max-width: 980px;
      margin: 0 auto 22px;
      text-align: left;
    }
    .brand {
      display:flex;
      align-items:center;
      gap:12px;
      margin-bottom:14px;
    }
    .logo {
      height:48px;
      width:48px;
      border-radius:10px;
      background: linear-gradient(135deg, var(--accent), #4bb77b);
      display:flex;
      align-items:center;
      justify-content:center;
      color:#fff;
      font-weight:700;
      box-shadow: 0 6px 18px rgba(15,15,15,0.06);
      font-family: Inter, sans-serif;
    }
    .welcome {
      font-size:20px;
      font-weight:600;
      margin:0;
    }
    .subtitle {
      margin:0;
      color:var(--muted);
      font-size:14px;
    }
    .card-wrap {
      max-width: 980px;
      margin: 18px auto;
    }
    .table thead th {
      border-bottom: 3px solid var(--accent-weak);
      background: transparent;
      color: var(--accent);
      font-weight:600;
    }
    .btn-accent {
      background: var(--accent);
      border-color: var(--accent);
      color:#fff;
    }
    .btn-accent-outline {
      color: var(--accent);
      border-color: var(--accent);
      background: transparent;
    }
    .card {
      border: none;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(16,16,16,0.06);
      overflow: hidden;
    }
    .table tbody tr:hover {
      background: rgba(47,143,74,0.03);
    }
    .small-muted { color:var(--muted); font-size:13px; }
    footer { text-align:center; color:var(--muted); margin-top:28px; font-size:13px; }
    @media (max-width:767px){
      .brand { gap:10px; }
      .welcome { font-size:18px; }
    }
  </style>
</head>
<body>
  <div class="hero">
    <div class="brand">
    <div class="logo">★</div>
    <div class="welcome-text">Добро пожаловать в систему управления клиентами</div>
    </div>
  </div>

  <div class="card-wrap">
    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="mb-0">Клиенты</h5>
          <div class="small-muted">Всего: <?= count($clients) ?></div>
        </div>
        <div>
          <a href="add.php" class="btn btn-accent">Добавить клиента</a>
        </div>
      </div>

      <?php if ($flash): ?>
        <div class="alert alert-success"><?= e($flash) ?></div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Имя</th>
              <th>Email</th>
              <th>Телефон</th>
              <th>Создан</th>
              <th>Статус</th>
              <th>Действия</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($clients)): ?>
              <tr>
                <td colspan="7" class="text-center small-muted py-4">Список пуст — добавьте первого клиента</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($clients as $c): ?>
              <tr>
                <td><?= e($c['id']) ?></td>
                <td><?= e($c['title']) ?></td>
                <td><a href="mailto:<?= e($c['email']) ?>"><?= e($c['email']) ?></a></td>
                <td><?= e($c['phone']) ?></td>
                <td class="small-muted"><?= e($c['created_at']) ?></td>
                <td><?= $c['status'] ? '<span class="badge bg-success">Активен</span>' : '<span class="badge bg-secondary">Неактивен</span>' ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= e($c['id']) ?>">Редактировать</a>

                  <form action="update_status.php" method="post" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <button type="submit" class="btn btn-sm btn-accent-outline">
                      <?= $c['status'] ? 'Деактивировать' : 'Активировать' ?>
                    </button>
                  </form>

                  <form action="delete.php" method="post" style="display:inline-block;" onsubmit="return confirm('Удалить клиента?');">
                    <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
  </div>
</body>
</html>
