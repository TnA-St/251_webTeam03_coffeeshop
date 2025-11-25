<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover; font-family: 'Segoe UI', sans-serif; overflow-x: hidden;
        }
        
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(40px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .navbar { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.8)!important; padding: 8px 16px!important; border-radius: 30px; margin: 0 5px; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(45deg, #0984e3, #6c5ce7); color: #fff!important; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); }
        .glass-panel {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 30px;
            margin-top: 30px; margin-bottom: 50px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: slideUpFade 0.6s ease forwards;
        }
        .table-rounded-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #00b894, #0984e3); border: none; color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); transition: 0.3s;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); color: white; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="glass-panel">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 border-light border-opacity-25">
            <h3 class="m-0 fw-bold text-white"><i class="bi bi-people-fill"></i> Danh Sách Nhân Viên</h3>
            <a href="add_user.php" class="btn btn-add rounded-pill px-4 py-2 fw-bold">
                <i class="bi bi-person-plus-fill"></i> Thêm User
            </a>
        </div>

        <?php if(isset($_SESSION['msg'])): ?>
            <div class="alert alert-success shadow-sm border-0 mb-4">
                <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="table-rounded-container">
            <table class="table table-hover align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 ps-4">ID</th>
                        <th class="py-3">Tên đăng nhập</th>
                        <th class="py-3">Họ và tên</th>
                        <th class="py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php 
                    $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
                    while ($user = $stmt->fetch()):
                        $is_me = ($user['id'] == $_SESSION['user_id']);
                    ?>
                    <tr class="<?= $is_me ? 'table-warning' : '' ?>">
                        <td class="ps-4 text-muted">#<?= $user['id'] ?></td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($user['fullname']) ?></td>
                        
                        <td class="text-center">
                            <?php if ($is_me): ?>
                                <span class="badge bg-success bg-opacity-75 rounded-pill px-3"><i class="bi bi-person-check-fill"></i> Chính bạn</span>
                            <?php else: ?>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-pill px-3 border-0 shadow-sm hover-scale"
                                   onclick="return confirm('CẢNH BÁO: Xóa nhân viên <?= $user['username'] ?>?')">
                                    <i class="bi bi-trash3-fill"></i> Xóa
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>