<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Xử lý thêm vào giỏ
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    
    // Nếu chưa có giỏ hàng thì tạo mới
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    // Nếu món đã có thì tăng số lượng, chưa có thì thêm mới
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty']++;
    } else {
        $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'qty' => 1];
    }
}

// Xử lý xóa khỏi giỏ
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: pos.php"); exit;
}

// Xử lý Thanh toán
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $customer = $_POST['customer_name'] ?: 'Khách vãng lai';
    $total = 0;
    foreach ($_SESSION['cart'] as $item) $total += $item['price'] * $item['qty'];

    // 1. Tạo đơn hàng
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, total) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $customer, $total]);
    $order_id = $pdo->lastInsertId();

    // 2. Lưu chi tiết đơn hàng
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $pid => $item) {
        $stmt_item->execute([$order_id, $pid, $item['qty'], $item['price']]);
    }

    // 3. Xóa giỏ hàng
    unset($_SESSION['cart']);
    $success = "Thanh toán thành công! Đơn hàng #$order_id";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>POS - Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* STYLE ĐỒNG BỘ */
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px); }
        .container-box {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 15px; padding: 20px; margin-top: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .product-card {
            background: rgba(255,255,255,0.8); border: none; transition: 0.2s;
            cursor: pointer;
        }
        .product-card:hover { transform: translateY(-5px); background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cup-hot"></i> Cafe Manager</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link" href="index.php">Sản phẩm</a>
            <a class="nav-link active" href="pos.php">💰 Bán Hàng</a>
            <a class="nav-link" href="orders.php">📄 Đơn Hàng</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="container-box">
                <h4 class="mb-3">☕ Thực Đơn</h4>
                <?php if(isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                
                <div class="row">
                    <?php
                    $products = $pdo->query("SELECT * FROM products")->fetchAll();
                    foreach($products as $p):
                    ?>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="product_name" value="<?= $p['name'] ?>">
                            <input type="hidden" name="product_price" value="<?= $p['price'] ?>">
                            <input type="hidden" name="add_to_cart" value="1">
                            
                            <button type="submit" class="card product-card w-100 h-100 p-3 text-center">
                                <h5 class="fw-bold mb-1"><?= htmlspecialchars($p['name']) ?></h5>
                                <span class="badge bg-info text-dark mb-2"><?= $p['category_name'] ?></span>
                                <h6 class="text-danger fw-bold"><?= number_format($p['price'], 0, ',', '.') ?> đ</h6>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="container-box">
                <h4 class="mb-3"><i class="bi bi-cart"></i> Hóa Đơn</h4>
                <table class="table table-sm">
                    <thead><tr><th>Món</th><th>SL</th><th>Tiền</th><th></th></tr></thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        if(isset($_SESSION['cart'])):
                            foreach($_SESSION['cart'] as $id => $item):
                                $line_total = $item['price'] * $item['qty'];
                                $grand_total += $line_total;
                        ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><b><?= $item['qty'] ?></b></td>
                            <td><?= number_format($line_total,0,',','.') ?></td>
                            <td><a href="?remove=<?= $id ?>" class="text-danger"><i class="bi bi-x-circle"></i></a></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between fw-bold fs-5 border-top pt-2">
                    <span>Tổng cộng:</span>
                    <span class="text-danger"><?= number_format($grand_total, 0, ',', '.') ?> đ</span>
                </div>

                <form method="POST" class="mt-3">
                    <div class="mb-2">
                        <input type="text" name="customer_name" class="form-control" placeholder="Tên khách hàng (Tùy chọn)">
                    </div>
                    <button type="submit" name="checkout" class="btn btn-success w-100 py-2 fw-bold">THANH TOÁN</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>