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
if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM clients WHERE id = :id');
    $stmt->execute([':id' => $id]);
    set_flash('Клиент удалён.');
} else {
    set_flash('Неверный ID для удаления.');
}
redirect('index.php');
?>