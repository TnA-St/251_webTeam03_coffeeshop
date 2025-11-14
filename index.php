<?php
require_once __DIR__ . '/config.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Quan Ly Quán Cà Phe</title>
  <style>body{font-family:Arial; margin:20px;} table{border-collapse:collapse; width:100%;} th,td{border:1px solid #ccc; padding:8px; text-align:left;} .actions a{margin-right:6px;}</style>
</head>
<body>
  <h1>Danh sách sản phẩm</h1>
  <p><a href="addnew.php">Thêm sản phẩm mới</a></p>
  <?php
  $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
  $products = $stmt->fetchAll();
  if (!$products) {
    echo '<p>Chưa có sản phẩm nào.</p>';
  } else {
    echo '<table><tr><th>ID</th><th>Tên</th><th>Giá</th><th>Tồn kho</th><th>Hành động</th></tr>';
    foreach ($products as $p) {
      echo '<tr>';
      echo '<td>'.htmlspecialchars($p['id']).'</td>';
      echo '<td>'.htmlspecialchars($p['name']).'</td>';
      echo '<td>'.number_format($p['price'],2).'</td>';
      echo '<td>'.htmlspecialchars($p['stock']).'</td>';
      echo '<td class="actions"><a href="edit.php?id='.urlencode($p['id']).'">Sửa</a> <a href="delete.php?id='.urlencode($p['id']).'" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')">Xóa</a></td>';
      echo '</tr>';
    }
    echo '</table>';
  }
  ?>
</body>
</html>
