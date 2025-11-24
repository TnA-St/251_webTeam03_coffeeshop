<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch Sử Đơn Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(40px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* CSS Navbar ghi đè */
        .navbar { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.8)!important; padding: 8px 16px!important; border-radius: 30px; transition: 0.3s; margin: 0 5px; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(45deg, #0984e3, #6c5ce7); color: #fff!important; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); }

        /* Glass Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 50px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: slideUpFade 0.6s ease forwards;
        }

        /* Table Container */
        .table-rounded-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .table-hover tbody tr:hover { background-color: rgba(108, 92, 231, 0.05); transition: 0.2s; }
        .price-tag { color: #d63031; font-weight: bold; font-family: monospace; font-size: 1.1em; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="glass-panel">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 border-light border-opacity-25">
            <h3 class="m-0 fw-bold text-white"><i class="bi bi-receipt-cutoff"></i> Lịch Sử Đơn Hàng</h3>
        </div>

        <?php if(isset($_SESSION['msg'])): ?>
            <div class="alert alert-success shadow-sm border-0 rounded-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="table-rounded-container">
            <table class="table table-hover align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 ps-4" width="10%">ID</th>
                        <th class="py-3" width="25%">Khách hàng</th>
                        <th class="py-3" width="20%">Thời gian</th>
                        <th class="py-3" width="20%">Tổng tiền</th>
                        <th class="py-3 text-center" width="25%">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php 
                    $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
                    $orders = $stmt->fetchAll();
                    
                    if (count($orders) > 0):
                        foreach($orders as $o): 
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-secondary">#<?= $o['id'] ?></td>
                        <td><span class="fw-bold text-dark"><?= htmlspecialchars($o['customer_name']) ?></span></td>
                        <td class="text-secondary"><i class="bi bi-clock-history"></i> <?= date('H:i d/m/Y', strtotime($o['order_date'])) ?></td>
                        <td class="price-tag"><?= number_format($o['total'], 0, ',', '.') ?> đ</td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" data-bs-toggle="collapse" href="#detail<?= $o['id'] ?>">
                                <i class="bi bi-eye-fill"></i> Chi tiết
                            </a>
                            <a href="delete_order.php?id=<?= $o['id'] ?>" 
                               class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                               onclick="return confirm('Xóa đơn #<?= $o['id'] ?>? Dữ liệu sẽ mất vĩnh viễn.')">
                               <i class="bi bi-trash3-fill"></i>
                            </a>
                        </td>
                    </tr>

                    <tr class="collapse bg-light" id="detail<?= $o['id'] ?>">
                        <td colspan="5" class="p-0 border-0">
                            <div class="p-4 border-bottom bg-light">
                                <div class="bg-white p-3 rounded shadow-sm border">
                                    <h6 class="text-primary fw-bold mb-3 pb-2 border-bottom">
                                        <i class="bi bi-basket2-fill"></i> Chi tiết món - Đơn #<?= $o['id'] ?>
                                    </h6>
                                    <div class="row g-2">
                                        <?php 
                                        $items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE order_id=?");
                                        $items->execute([$o['id']]);
                                        foreach($items as $i):
                                        ?>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded border">
                                                <span>
                                                    <i class="bi bi-dot text-primary"></i> <?= htmlspecialchars($i['name']) ?> 
                                                    <span class="badge bg-secondary rounded-pill ms-1">x<?= $i['quantity'] ?></span>
                                                </span>
                                                <span class="fw-bold text-dark"><?= number_format($i['price'] * $i['quantity'], 0, ',', '.') ?> đ</span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="text-end mt-3 pt-2 border-top">
                                        <small class="text-muted text-uppercase fw-bold">Tổng cộng:</small>
                                        <span class="fs-5 fw-bold text-danger ms-2"><?= number_format($o['total'], 0, ',', '.') ?> đ</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-1 opacity-25"></i><br>
                            <span class="mt-3 d-block">Chưa có đơn hàng nào.</span>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>