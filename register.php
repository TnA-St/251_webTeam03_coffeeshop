<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $full = trim($_POST['fullname']);
    
    if(empty($user) || empty($pass) || empty($full)) {
        $msg = '<div class="alert alert-warning border-0 shadow-sm"><i class="bi bi-exclamation-triangle-fill"></i> Vui lòng điền đầy đủ thông tin!</div>';
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$user]);
        
        if ($check->rowCount() > 0) {
            $msg = '<div class="alert alert-danger border-0 shadow-sm"><i class="bi bi-x-circle-fill"></i> Tên đăng nhập đã tồn tại!</div>';
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, role) VALUES (?, ?, ?, 'nhanvien')");
            
            if ($stmt->execute([$user, $hashed, $full])) {
                $msg = '<div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle-fill"></i> Đăng ký thành công! <a href="login.php" class="fw-bold text-success">Đăng nhập ngay</a></div>';
            } else {
                $msg = '<div class="alert alert-danger border-0 shadow-sm">Lỗi hệ thống, vui lòng thử lại.</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản</title>
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
            max-width: 450px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.6);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-label { font-weight: bold; color: #495057; font-size: 0.9rem; }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            padding-left: 45px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #6c5ce7;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.15);
        }

        .input-group-icon { position: relative; }
        .input-group-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.1rem;
            z-index: 10;
        }

        .btn-register {
            background: linear-gradient(135deg, #00b894, #0984e3);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.4);
            color: white;
        }

        .link-login {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }
        .link-login:hover { color: #0984e3; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="glass-login">
        <div class="text-center mb-4">
            <h1 class="text-primary fw-bold mb-2"><i class="bi bi-person-plus-fill"></i></h1>
            <h4 class="fw-bold text-dark">Đăng Ký Tài Khoản</h4>
            <p class="text-muted small">Tham gia hệ thống quản lý Cafe</p>
        </div>

        <?= $msg ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <div class="input-group-icon">
                    <i class="bi bi-person-vcard-fill"></i>
                    <input type="text" name="fullname" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <div class="input-group-icon">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="username" class="form-control" placeholder="Viết liền không dấu" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group-icon">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100 mb-3">
                Đăng Ký Ngay
            </button>
        </form>

        <div class="text-center pt-3 border-top border-secondary border-opacity-10">
            <small class="text-muted">Đã có tài khoản?</small> 
            <a href="login.php" class="link-login ms-1">Đăng nhập tại đây</a>
        </div>
    </div>

</body>
</html>
