<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE products SET name=?, category_name=?, price=?, stock=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['name'], $_POST['category_name'], $_POST['price'], $_POST['stock'], $id]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Sửa Món</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title mb-3">Sửa món</h4>
            <form method="POST">
                <div class="mb-3"><label>Tên món</label><input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" required></div>
                <div class="mb-3">
                    <label>Danh mục</label>
                    <select name="category_name" class="form-select">
                        <?php 
                        $cats = ['Cà phê', 'Trà & Sinh tố', 'Đồ ăn nhẹ', 'Khác'];
                        foreach($cats as $c) {
                            $sel = ($c == $product['category_name']) ? 'selected' : '';
                            echo "<option value='$c' $sel>$c</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3"><label>Giá</label><input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required></div>
                <div class="mb-3"><label>Tồn kho</label><input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>"></div>
                <button class="btn btn-warning w-100">Cập nhật</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>