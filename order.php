<?php
require_once __DIR__ . '/config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #2d3436; color: #fff; padding: 20px; }
        .container-box { background: rgba(255,255,255,0.9); color: #000; padding: 20px; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="mb-3">
            <a href="index.php" class="btn btn-outline-light">&larr; Quay lại Sản phẩm</a>
        </div>
        <div class="container-box">
            <h3 class="mb-4">🧾 Danh sách Đơn hàng</h3>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Đơn</th>
                        <th>Ngày đặt</th>
                        <th>Nhân viên tạo</th>
                        <th>Tổng tiền</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // JOIN các bảng để lấy tên nhân viên
                    $sql = "SELECT o.*, u.fullname as staff_name 
                            FROM orders o 
                            LEFT JOIN users u ON o.user_id = u.id 
                            ORDER BY o.order_date DESC";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['order_date'])) ?></td>
                        <td><?= htmlspecialchars($row['staff_name'] ?? 'Khách vãng lai') ?></td>
                        <td class="fw-bold text-danger"><?= number_format($row['total'], 0) ?> đ</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">Xem món</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php if($stmt->rowCount() == 0) echo "<p class='text-center'>Chưa có đơn hàng nào.</p>"; ?>
        </div>
    </div>
</body>
</html>