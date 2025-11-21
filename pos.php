<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id']; $name = $_POST['product_name']; $price = $_POST['product_price'];
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]['qty']++;
    else $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'qty' => 1];
}
if (isset($_GET['remove'])) { unset($_SESSION['cart'][$_GET['remove']]); header("Location: pos.php"); exit; }
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $customer = $_POST['customer_name'] ?: 'Khách';
    $total = 0; foreach ($_SESSION['cart'] as $item) $total += $item['price'] * $item['qty'];
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, total) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $customer, $total]);
    $order_id = $pdo->lastInsertId();
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $pid => $item) $stmt_item->execute([$order_id, $pid, $item['qty'], $item['price']]);
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
        /* === 1. ANIMATION & BASE STYLES === */
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover; font-family: 'Segoe UI', sans-serif; overflow-x: hidden;
        }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(40px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        
        /* === 2. NAVBAR === */
        .navbar { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); transition: 0.3s; }
        .nav-link { color: rgba(255,255,255,0.7)!important; padding: 8px 16px!important; border-radius: 30px; transition: 0.3s; margin: 0 5px; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: #fff!important; transform: translateY(-2px); }
        .nav-link.active { background: linear-gradient(45deg, #0984e3, #6c5ce7); color: #fff!important; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); transform: scale(1.05); }

        /* === 3. CONTAINER & CARDS === */
        .container-box {
            background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.5); border-radius: 20px; padding: 20px; margin-top: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: slideUpFade 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        
        /* Hiệu ứng riêng cho thẻ sản phẩm */
        .product-card {
            background: rgba(255,255,255,0.8); border: none; border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); cursor: pointer;
        }
        .product-card:hover { 
            transform: translateY(-8px) scale(1.02); 
            background: #fff; 
            box-shadow: 0 12px 25px rgba(0,0,0,0.15); 
        }
        .product-card:active { transform: scale(0.95); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-cup-hot-fill"></i> Cafe Manager</a>
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
                <?php if(isset($success)) echo "<div class='alert alert-success shadow-sm'>$success</div>"; ?>
                
                <div class="row g-3">
                    <?php
                    $products = $pdo->query("SELECT * FROM products")->fetchAll();
                    foreach($products as $p):
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <form method="POST" class="h-100">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="product_name" value="<?= $p['name'] ?>">
                            <input type="hidden" name="product_price" value="<?= $p['price'] ?>">
                            <input type="hidden" name="add_to_cart" value="1">
                            
                            <button type="submit" class="card product-card w-100 h-100 p-3 text-center">
                                <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($p['name']) ?></h5>
                                <span class="badge bg-info bg-opacity-25 text-primary mb-2 border border-info border-opacity-25"><?= $p['category_name'] ?></span>
                                <h6 class="text-danger fw-bold m-0"><?= number_format($p['price'], 0, ',', '.') ?> đ</h6>
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
                <table class="table table-sm align-middle">
                    <thead><tr><th>Món</th><th class="text-center">SL</th><th class="text-end">Tiền</th><th></th></tr></thead>
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
                            <td class="text-center fw-bold"><?= $item['qty'] ?></td>
                            <td class="text-end"><?= number_format($line_total,0,',','.') ?></td>
                            <td class="text-end"><a href="?remove=<?= $id ?>" class="text-danger"><i class="bi bi-x-circle-fill"></i></a></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between fw-bold fs-5 border-top border-dark border-opacity-10 pt-3 mt-2">
                    <span>Tổng cộng:</span>
                    <span class="text-danger"><?= number_format($grand_total, 0, ',', '.') ?> đ</span>
                </div>

                <form method="POST" class="mt-4">
                    <div class="mb-2">
                        <input type="text" name="customer_name" class="form-control bg-white bg-opacity-50" placeholder="Tên khách hàng...">
                    </div>
                    <button type="submit" name="checkout" class="btn btn-success w-100 py-2 fw-bold shadow-sm">THANH TOÁN</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>