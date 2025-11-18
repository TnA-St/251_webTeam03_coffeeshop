<?php
require_once __DIR__ . '/config.php';

// Nếu đã đăng nhập rồi thì chuyển về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Tìm user theo tên đăng nhập
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 2. KIỂM TRA MẬT KHẨU (KHÔNG DÙNG HASH)
    // So sánh trực tiếp: Nếu password nhập vào === password trong DB
    if ($user && $password === $user['password']) {
        // Đăng nhập thành công -> Lưu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        
        header('Location: index.php');
        exit;
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập (No Hash)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #2d3436;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="text-center mb-4">☕ Login Admin</h3>
        
        <?php if($error): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Nhập admin">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Nhập password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </form>
        
        <div class="mt-3 text-center text-muted small">
            User: <b>admin</b> | Pass: <b>password</b>
        </div>
    </div>
</body>
</html>