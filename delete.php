<?php
session_start();
require_once 'config.php';
if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: index.php");
?>