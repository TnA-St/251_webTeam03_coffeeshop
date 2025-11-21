<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$order_id = $_GET['id'] ?? 0;

// Lấy thông tin đơn hàng
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) { echo "Không tìm thấy đơn hàng!"; exit; }

// Lấy danh sách món trong đơn
$sql_items = "SELECT oi.*, p.name as product_name 
              FROM order_items oi 
              LEFT JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = ?";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Chi Tiết Đơn #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover; font-family: 'Segoe UI', sans-serif;
        }
        .container-box {
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px);
            border-radius: 15px; padding: 40px; margin-top: 50px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1); max-width: 700px; margin-left: auto; margin-right: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="container-box">
        <div class="text-center mb-4">
            <h3>HÓA ĐƠN THANH TOÁN</h3>
            <p class="text-muted">Mã đơn: #<?= $order['id'] ?></p>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?><br>
                <strong>Ngày:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?>
            </div>
            <div class="col-6 text-end">
                <strong>Thu ngân:</strong> <?= htmlspecialchars($_SESSION['fullname']) ?>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tên món</th>
                    <th class="text-center">SL</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-end"><?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td class="text-end fw-bold"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold fs-5">TỔNG CỘNG:</td>
                    <td class="text-end fw-bold fs-5 text-danger"><?= number_format($order['total'], 0, ',', '.') ?> đ</td>
                </tr>
            </tfoot>
        </table>

        <div class="text-center mt-4">
            <a href="orders.php" class="btn btn-secondary">Quay lại danh sách</a>
            <button onclick="window.print()" class="btn btn-primary">In hóa đơn</button>
        </div>
    </div>
</div>

</body>
</html>