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
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px); }
        .container-box {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 15px; padding: 30px; margin-top: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cup-hot"></i> Cafe Manager</a>
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
        // Lấy danh sách đơn hàng giảm dần theo thời gian
        $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
        $orders = $stmt->fetchAll();
        ?>

        <table class="table table-hover shadow-sm" style="background: rgba(255,255,255,0.8); border-radius: 10px;">
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