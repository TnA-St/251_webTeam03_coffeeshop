<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $full = $_POST['fullname'];

    // Kiểm tra user tồn tại
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$user]);
    
    if ($check->rowCount() > 0) {
        $msg = '<div class="alert alert-danger">Tên đăng nhập đã tồn tại!</div>';
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, role) VALUES (?, ?, ?, 'nhanvien')");
        if ($stmt->execute([$user, $hashed, $full])) {
            $msg = '<div class="alert alert-success">Đăng ký thành công! <a href="login.php">Đăng nhập</a></div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .glass-box {
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);
            padding: 40px; border-radius: 15px; width: 100%; max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="glass-box">
        <h3 class="text-center mb-4">📝 Đăng Ký</h3>
        <?= $msg ?>
        <form method="POST">
            <div class="mb-2"><label>Họ tên</label><input type="text" name="fullname" class="form-control" required></div>
            <div class="mb-2"><label>Username</label><input type="text" name="username" class="form-control" required></div>
            <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-success w-100">Đăng Ký</button>
        </form>
        <div class="text-center mt-3"><a href="login.php">Quay lại đăng nhập</a></div>
    </div>
</body>
</html>