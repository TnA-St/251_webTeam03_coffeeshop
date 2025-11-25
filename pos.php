<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['quick_add_table'])) {
    $new_table_name = trim($_POST['new_table_name']);
    if (!empty($new_table_name)) {
        try {
            $pdo->prepare("INSERT INTO tables (name) VALUES (?)")->execute([$new_table_name]);
        } catch (Exception $e) {}
    }
}

if (isset($_POST['quick_add_customer'])) {
    $new_name = trim($_POST['new_cust_name']);
    $new_phone = trim($_POST['new_cust_phone']);
    if (!empty($new_name)) {
        try {
            $pdo->prepare("INSERT INTO customers (name, phone) VALUES (?, ?)")->execute([$new_name, $new_phone]);
        } catch (Exception $e) {}
    }
}

if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id']; 
    $name = $_POST['product_name']; 
    $price = $_POST['product_price'];
    
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty']++;
    } else {
        $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'qty' => 1];
    }
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: pos.php");
    exit;
}

if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $table_id = $_POST['table_id'];
    $customer_id = $_POST['customer_id'];
    
    // Logic xác định tên khách
    if ($customer_id == 1) {
        $custName = 'Khách'; // Đổi tên mặc định thành "Khách"
    } else {
        $stmtCust = $pdo->prepare("SELECT name FROM customers WHERE id = ?");
        $stmtCust->execute([$customer_id]);
        $custName = $stmtCust->fetchColumn() ?: 'Khách';
    }

    $total = 0; 
    foreach ($_SESSION['cart'] as $item) $total += $item['price'] * $item['qty'];

    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, total, table_id, customer_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $custName, $total, $table_id, $customer_id]);
        $order_id = $pdo->lastInsertId();

        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        foreach ($_SESSION['cart'] as $pid => $item) {
            $stmt_item->execute([$order_id, $pid, $item['qty'], $item['price']]);
            $stmt_stock->execute([$item['qty'], $pid]);
        }
        
        $pdo->commit();
        unset($_SESSION['cart']);
        $success = "Thanh toán thành công đơn #$order_id!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Lỗi thanh toán: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>POS - Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .navbar { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); flex-shrink: 0; }
        .nav-link { color: rgba(255,255,255,0.8)!important; padding: 8px 16px!important; border-radius: 30px; transition: 0.3s; margin: 0 5px; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(45deg, #0984e3, #6c5ce7); color: #fff!important; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); }

        .main-content {
            flex: 1;
            overflow: hidden;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .menu-panel {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .menu-scroll { overflow-y: auto; flex: 1; padding-right: 5px; }

        .product-card {
            background: white;
            border: none;
            border-radius: 15px;
            transition: all 0.2s;
            cursor: pointer;
            min-height: 110px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }

        .bill-panel {
            background: #fff;
            border-radius: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.5);
        }

        .bill-header { background: #f8f9fa; padding: 15px; border-bottom: 2px dashed #dee2e6; text-align: center; flex-shrink: 0; }
        .bill-body { flex: 1; overflow-y: auto; padding: 15px; background: #fff; }
        .bill-footer { background: #f1f3f5; padding: 15px; border-top: 2px dashed #dee2e6; flex-shrink: 0; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.2); border-radius: 10px; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container-fluid px-4 main-content">
    <div class="row g-3 h-100">
        
        <div class="col-md-8 h-100">
            <div class="menu-panel">
                <h4 class="text-white fw-bold mb-3"><i class="bi bi-grid-fill"></i> Thực Đơn</h4>
                
                <?php if(isset($success)) echo "<div class='alert alert-success py-2 border-0 shadow-sm'><i class='bi bi-check-circle'></i> $success</div>"; ?>
                <?php if(isset($error)) echo "<div class='alert alert-danger py-2 border-0 shadow-sm'><i class='bi bi-exclamation-circle'></i> $error</div>"; ?>
                
                <div class="menu-scroll">
                    <div class="row g-2">
                        <?php
                        $products = $pdo->query("SELECT * FROM products ORDER BY category_name ASC")->fetchAll();
                        foreach($products as $p):
                        ?>
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <form method="POST" class="h-100">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="product_name" value="<?= $p['name'] ?>">
                                <input type="hidden" name="product_price" value="<?= $p['price'] ?>">
                                <input type="hidden" name="add_to_cart" value="1">
                                
                                <button type="submit" class="product-card w-100 h-100 p-3 d-flex flex-column justify-content-between position-relative text-start">
                                    <div>
                                        <div class="badge bg-light text-secondary border mb-1"><?= htmlspecialchars($p['category_name']) ?></div>
                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem"><?= htmlspecialchars($p['name']) ?></h6>
                                    </div>
                                    <h5 class="text-primary fw-bold mt-2 mb-0"><?= number_format($p['price'], 0, ',', '.') ?></h5>
                                    
                                    <?php if($p['stock'] < 10): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                                            <?= $p['stock'] ?>
                                        </span>
                                    <?php endif; ?>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 h-100">
            <div class="bill-panel">
                <div class="bill-header">
                    <h5 class="fw-bold m-0 text-secondary"><i class="bi bi-receipt"></i> HÓA ĐƠN</h5>
                    <small class="text-muted"><?= date('d/m/Y H:i') ?></small>
                </div>

                <div class="bill-body">
                    <?php if(empty($_SESSION['cart'])): ?>
                        <div class="text-center text-muted mt-5 opacity-50">
                            <i class="bi bi-cart-x display-1"></i>
                            <p class="mt-2">Chưa có món nào</p>
                        </div>
                    <?php else: ?>
                        <table class="table table-borderless align-middle m-0">
                            <thead class="text-secondary small border-bottom">
                                <tr><th>Món</th><th class="text-center">SL</th><th class="text-end">Tiền</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grand_total = 0;
                                foreach($_SESSION['cart'] as $id => $item):
                                    $line_total = $item['price'] * $item['qty'];
                                    $grand_total += $line_total;
                                ?>
                                <tr class="border-bottom border-light">
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($item['name']) ?></div>
                                        <small class="text-muted"><?= number_format($item['price']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill"><?= $item['qty'] ?></span>
                                    </td>
                                    <td class="text-end fw-bold"><?= number_format($line_total) ?></td>
                                    <td class="text-end">
                                        <a href="?remove=<?= $id ?>" class="text-danger small"><i class="bi bi-x-circle-fill"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="bill-footer">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold">Tổng tiền:</span>
                        <span class="text-danger fw-bold fs-3"><?= number_format($grand_total ?? 0) ?> đ</span>
                    </div>

                    <form method="POST">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small fw-bold text-secondary mb-1">
                                    <i class="bi bi-diagram-2"></i> Bàn 
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addTableModal" class="text-primary text-decoration-none">(+)</a>
                                </label>
                                <select name="table_id" class="form-select form-select-sm">
                                    <option value="0">Chọn bàn</option>
                                    <?php foreach($pdo->query("SELECT * FROM tables") as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small fw-bold text-secondary mb-1">
                                    <i class="bi bi-person"></i> Khách
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCustModal" class="text-primary text-decoration-none">(+)</a>
                                </label>
                                <select name="customer_id" class="form-select form-select-sm">
                                    <option value="1">Khách</option>
                                    <?php foreach($pdo->query("SELECT * FROM customers WHERE id != 1") as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="checkout" class="btn btn-primary w-100 py-3 fw-bold text-uppercase shadow-sm" 
                                style="background: linear-gradient(135deg, #6c5ce7, #0984e3); border: none;">
                            <i class="bi bi-credit-card-2-back me-2"></i> Thanh Toán
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCustModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Khách Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3"><label>Tên khách</label><input type="text" name="new_cust_name" class="form-control" required></div>
                    <input type="hidden" name="quick_add_customer" value="1">
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary w-100">Lưu</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addTableModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Bàn Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3"><label>Tên bàn</label><input type="text" name="new_table_name" class="form-control" required></div>
                    <input type="hidden" name="quick_add_table" value="1">
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary w-100">Lưu</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
