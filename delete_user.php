<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $current_user_id = $_SESSION['user_id'];
    if ($id == $current_user_id) {
        $_SESSION['error'] = "Không thể tự xóa tài khoản đang đăng nhập!";
    } else {
        try {
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);

            $pdo->exec("ALTER TABLE users AUTO_INCREMENT = 1");
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            $_SESSION['msg'] = "Đã xóa nhân viên #$id. ID tiếp theo sẽ được tính lại (nếu xóa người cuối).";
        } catch (Exception $e) {
            $_SESSION['error'] = "Lỗi khi xóa: " . $e->getMessage();
        }
    }
}
header("Location: user.php");
exit;
?>