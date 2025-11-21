<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        
        $stmtItem = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmtItem->execute([$id]);
        $pdo->exec("ALTER TABLE orders AUTO_INCREMENT = 1");
        $pdo->exec("ALTER TABLE order_items AUTO_INCREMENT = 1");

        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        $_SESSION['msg'] = "Đã xóa đơn hàng #$id. Đơn tiếp theo sẽ lấy lại ID này (nếu đây là đơn cuối).";

    } catch (Exception $e) {
    }
}
header("Location: orders.php");
exit;
?>