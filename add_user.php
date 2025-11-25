<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    if (empty($username) || empty($password) || empty($fullname)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($password !== $re_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Tên đăng nhập này đã có người sử dụng!";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hashed_password, $fullname]);
                $success = "Tạo tài khoản thành công! <a href='user.php'>Quay lại danh sách</a>";
            } catch (PDOException $e) {
                $error = "Lỗi: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Tài Khoản Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover; font-family: 'Segoe UI', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.5); border-radius: 20px; padding: 40px; 
            max-width: 600px; margin: 50px auto; box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .form-control { border-radius: 10px; padding: 12px; }
        .btn-submit { background: linear-gradient(135deg, #6c5ce7, #0984e3); border: none; color: white; padding: 12px; border-radius: 10px; font-weight: bold; }
        .btn-submit:hover { opacity: 0.9; color: white; transform: translateY(-2px); transition: 0.3s; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="glass-panel">
        <h3 class="text-center mb-4 fw-bold text-dark"><i class="bi bi-person-plus-fill text-primary"></i> Thêm Nhân Viên</h3>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Họ và tên</label>
                <input type="text" name="fullname" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" placeholder="Viết liền không dấu" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                    <input type="password" name="re_password" class="form-control" required>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-submit">Tạo Tài Khoản</button>
                <a href="user.php" class="btn btn-light rounded-3">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>