<?php
session_start();
require_once 'config.php';

// Nếu chưa đăng nhập thì về trang login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px);
        }
        .container-box {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 15px; padding: 30px; margin-top: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .badge-cat { background: #6c5ce7; color: #fff; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;}
        .price-tag { color: #d63031; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-cup-hot"></i> Cafe Manager</a>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Hi, <b><?= htmlspecialchars($_SESSION['fullname']) ?></b></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger">Đăng xuất</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>📦 Danh sách món</h3>
            <a href="addnew.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm món</a>
        </div>

        <?php
        $stmt = $pdo->query("SELECT * FROM products ORDER BY category_name ASC, name ASC");
        $products = $stmt->fetchAll();
        ?>

        <table class="table table-hover shadow-sm" style="background: rgba(255,255,255,0.8); border-radius: 10px; overflow: hidden;">
            <thead class="table-dark">
                <tr>
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                    <td><span class="badge-cat"><?= htmlspecialchars($p['category_name']) ?></span></td>
                    <td class="price-tag"><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
                    <td>
                        <?php if($p['stock'] < 10): ?>
                            <span class="text-danger fw-bold"><?= $p['stock'] ?> (Sắp hết)</span>
                        <?php else: ?>
                            <span class="text-success"><?= $p['stock'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa món này?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>