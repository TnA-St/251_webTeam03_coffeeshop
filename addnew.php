<?php
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $price = $_POST['price'] ?? 0;
  $stock = $_POST['stock'] ?? 0;
  $stmt = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (?, ?, ?)');
  $stmt->execute([$name, $price, $stock]);
  header('Location: index.php');
  exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Thêm sản phẩm</title></head><body>
<h1>Thêm sản phẩm</h1>
<form method="post">
  <label>Tên sản phẩm:<br><input type="text" name="name" required></label><br>
  <label>Giá:<br><input type="number" name="price" step="0.01" required></label><br>
  <label>Tồn kho:<br><input type="number" name="stock" required></label><br>
  <button type="submit">Thêm</button> <a href="index.php"><button type="button">Hủy</button></a>
</form>
</body></html>
