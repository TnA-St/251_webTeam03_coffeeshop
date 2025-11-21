<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Lịch Sử Đơn Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* === 1. BASE STYLES & ANIMATION === */
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover; font-family: 'Segoe UI', sans-serif; overflow-x: hidden;
        }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(40px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        
        /* === 2. NAVBAR === */
        .navbar { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.7)!important; padding: 8px 16px!important; border-radius: 30px; transition: 0.3s; margin: 0 5px; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: #fff!important; transform: translateY(-2px); }
        .nav-link.active { background: linear-gradient(45deg, #0984e3, #6c5ce7); color: #fff!important; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); transform: scale(1.05); }

        /* === 3. CONTAINER === */
        .container-box {
            background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.5); border-radius: 20px; padding: 30px; margin-top: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-cup-hot-fill"></i> Cafe Manager</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link" href="index.php">Sản phẩm</a>
            <a class="nav-link" href="pos.php">💰 Bán Hàng</a>
            <a class="nav-link active" href="orders.php">📄 Đơn Hàng</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-box">
        <h3 class="mb-4">📄 Lịch Sử Đơn Hàng</h3>
        
        <?php
        $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
        $orders = $stmt->fetchAll();
        ?>

        <table class="table table-hover align-middle shadow-sm" style="background: rgba(255,255,255,0.6); border-radius: 15px; overflow: hidden;">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Thời gian</th>
                    <th>Tổng tiền</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= $o['id'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td><?= date('H:i d/m/Y', strtotime($o['order_date'])) ?></td>
                    <td class="text-danger fw-bold"><?= number_format($o['total'], 0, ',', '.') ?> đ</td>
                    <td>
                        <a href="order_detail.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Xem</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>