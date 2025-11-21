<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO products (name, category_name, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['name'], $_POST['category_name'], $_POST['price'], $_POST['stock']]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Món</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title mb-3">Thêm món mới</h4>
            <form method="POST">
                <div class="mb-3"><label>Tên món</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_name" class="form-select">
                        <option>Cà phê</option><option>Trà & Sinh tố</option><option>Đồ ăn nhẹ</option><option>Khác</option>
                    </select>
                </div>
                <div class="mb-3"><label>Giá</label><input type="number" name="price" class="form-control" required></div>
                <div class="mb-3"><label>Tồn kho</label><input type="number" name="stock" class="form-control" value="50"></div>
                <button class="btn btn-primary w-100">Lưu lại</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>