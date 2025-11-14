<?php
require_once __DIR__ . '/config.php';
if (!isset($_GET['id'])) { header('Location: index.php'); exit; }
$id = (int) $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $price = $_POST['price'] ?? 0;
  $stock = $_POST['stock'] ?? 0;
  $stmt = $pdo->prepare('UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?');
  $stmt->execute([$name, $price, $stock, $id]);
  header('Location: index.php'); exit;
}
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?'); $stmt->execute([$id]); $p = $stmt->fetch(); if (!$p) { echo 'Không tìm thấy sản phẩm'; exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Sửa sản phẩm</title></head><body>
<h1>Sửa sản phẩm</h1>
<form method="post">
  <label>Tên:<br><input type="text" name="name" value="<?php echo htmlspecialchars($p['name']); ?>"></label><br>
  <label>Giá:<br><input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($p['price']); ?>"></label><br>
  <label>Tồn kho:<br><input type="number" name="stock" value="<?php echo htmlspecialchars($p['stock']); ?>"></label><br>
  <button type="submit">Lưu</button> <a href="index.php"><button type="button">Hủy</button></a>
</form>
</body></html>
