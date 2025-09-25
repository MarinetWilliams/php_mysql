<?php
require_once 'config.php';
require_once 'helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('Неправильный запрос');
    redirect('index.php');
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Неверный CSRF токен');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    set_flash('Неверный ID');
    redirect('index.php');
}

$stmt = $pdo->prepare('SELECT is_active FROM clients WHERE id = :id');
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    set_flash('Клиент не найден.');
    redirect('index.php');
}

$new = $row['is_active'] ? 0 : 1;
$upd = $pdo->prepare('UPDATE clients SET is_active = :new WHERE id = :id');
$upd->execute([':new' => $new, ':id' => $id]);

set_flash($new ? 'Клиент активирован.' : 'Клиент деактивирован.');
redirect('index.php');
?>