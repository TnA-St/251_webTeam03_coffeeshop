<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $error = 'Sai tài khoản hoặc mật khẩu!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập Hệ Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .glass-login {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.6);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-label { font-weight: bold; color: #495057; }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            padding-left: 40px; /* Chừa chỗ cho icon */
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #6c5ce7;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.15);
        }

        /* Container chứa input có icon */
        .input-group-icon { position: relative; }
        .input-group-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        .btn-login {
            background: linear-gradient(135deg, #6c5ce7, #0984e3);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
            color: white;
        }

        .link-register {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }
        .link-register:hover { color: #0984e3; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="glass-login">
        <div class="text-center mb-4">
            <h1 class="text-primary fw-bold mb-2"><i class="bi bi-cup-hot-fill"></i></h1>
            <h4 class="fw-bold text-dark">Quản Lý Quán Cafe</h4>
            <p class="text-muted small">Đăng nhập để vào hệ thống quản lý</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger py-2 rounded-3 border-0 shadow-sm mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Tài khoản</label>
                <div class="input-group-icon">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="username" class="form-control" placeholder="Nhập username..." required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group-icon">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-3">
                Đăng Nhập <i class="bi bi-arrow-right-short"></i>
            </button>
        </form>

        <div class="text-center pt-3 border-top border-secondary border-opacity-10">
            <small class="text-muted">Chưa có tài khoản?</small> 
            <a href="register.php" class="link-register">Tạo nhân viên mới</a>
        </div>
    </div>

</body>
</html>
