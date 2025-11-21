<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* === 1. CẤU TRÚC & NỀN === */
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden; /* Tránh thanh cuộn ngang khi animation chạy */
        }

        /* === 2. ANIMATION DEFINITIONS === */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(40px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* === 3. NAVBAR GLASS & ANIMATION === */
        .navbar {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 500; margin: 0 5px; padding: 8px 16px !important;
            border-radius: 30px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15); color: #fff !important;
            transform: translateY(-2px);
        }
        .nav-link.active {
            background: linear-gradient(45deg, #0984e3, #6c5ce7);
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4);
            transform: scale(1.05);
        }

        /* === 4. CONTAINER BOX ANIMATION === */
        .container-box {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px; padding: 30px; margin-top: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            
            /* Kích hoạt Animation */
            animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* === 5. ELEMENT STYLES === */
        .badge-cat { background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .price-tag { color: #d63031; font-weight: bold; }
        .btn { border-radius: 10px; transition: transform 0.1s; }
        .btn:active { transform: scale(0.95); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-cup-hot-fill"></i> Cafe Manager</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="pos.php">💰 Bán Hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">📄 Đơn Hàng</a></li>
            </ul>
        </div>
        <div class="d-flex text-white align-items-center">
            <span class="me-3">Hi, <b><?= htmlspecialchars($_SESSION['fullname']) ?></b></span>
            <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3">Đăng xuất</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>📦 Quản Lý Sản Phẩm</h3>
            <a href="addnew.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg"></i> Thêm món</a>
        </div>

        <?php
        $stmt = $pdo->query("SELECT * FROM products ORDER BY category_name ASC, name ASC");
        $products = $stmt->fetchAll();
        ?>

        <table class="table table-hover align-middle shadow-sm" style="background: rgba(255,255,255,0.6); border-radius: 15px; overflow: hidden;">
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
                    <td><?= $p['stock'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                        <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>