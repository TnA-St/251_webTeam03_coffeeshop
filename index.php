<?php
require_once __DIR__ . '/config.php';

// Kiểm tra xem session đã được start chưa, nếu chưa thì start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Quán Cà Phê</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            /* Ảnh nền */
            background-image: url('background-image.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-color: #2d3436;
            font-family: 'Segoe UI', sans-serif;
            padding-bottom: 50px;
        }

        /* === NAVBAR GLASS UI === */
        .navbar {
            background: rgba(45, 52, 54, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            color: #fff !important;
            font-weight: 600;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
        }

        /* === CONTENT BOX GLASS UI === */
        .container-box {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15);
            padding: 30px;
            border-radius: 14px;
            margin-top: 30px;
        }

        /* TABLE STYLES */
        .table thead {
            background: #2d3436;
            color: #fff;
        }
        .table.table-bordered {
            border-color: rgba(0, 0, 0, 0.15);
        }
        .table-hover > tbody > tr:hover > * {
            background-color: rgba(255, 255, 255, 0.4);
            color: #000;
        }

        /* BUTTON STYLES */
        .btn-custom {
            border-radius: 8px;
            padding: 8px 14px;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-add { background: #0984e3; color: #fff; }
        .btn-add:hover { background: #74b9ff; color: #000; }
        
        .btn-edit { background: #fdcb6e; color: #000; }
        .btn-edit:hover { background: #ffeaa7; }
        
        .btn-del { background: #d63031; color: #fff; }
        .btn-del:hover { background: #ff7675; color: #000; }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-cup-hot-fill"></i> Quản Lý Cà Phê
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="bi bi-box-seam"></i> Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php"><i class="bi bi-receipt"></i> Đơn hàng</a>
                    </li>
                    <li class="nav-item ms-3">
                        <span class="text-white me-2">Xin chào, <b><?= htmlspecialchars($_SESSION['fullname']) ?></b></span>
                        <a href="logout.php" class="btn btn-sm btn-outline-danger">Đăng xuất</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-person-circle"></i> Đăng nhập Admin
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="container-box">

        <h3 class="mb-4 text-center">📦 DANH SÁCH SẢN PHẨM</h3>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="alert alert-warning text-center shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill"></i> Lưu ý: Hệ thống đang chạy thử nghiệm.<br>
                Tài khoản: <b>admin</b> | Mật khẩu: <b>password</b>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="text-end mb-3">
            <a href="addnew.php" class="btn btn-custom btn-add">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm
            </a>
        </div>
        <?php endif; ?>

        <?php
        try {
            if (!isset($pdo)) {
                 echo '<div class="alert alert-danger text-center">Lỗi: Không thể kết nối đến cơ sở dữ liệu. Vui lòng kiểm tra tệp config.php.</div>';
            } else {
                $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
                $products = $stmt->fetchAll();

                if (!$products) {
                    echo '<div class="alert alert-warning text-center">❌ Chưa có sản phẩm nào.</div>';
                } else {
        ?>
        
        <table class="table table-hover table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Tên sản phẩm</th>
                    <th width="140">Giá</th>
                    <th width="100">Tồn kho</th>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <th width="160">Hành động</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>

        <?php
                    foreach ($products as $p) {
                        echo "<tr>";
                        echo "<td>{$p['id']}</td>";
                        echo "<td>{$p['name']}</td>";
                        echo "<td>" . number_format($p['price'], 0) . " đ</td>";
                        echo "<td>{$p['stock']}</td>";
                        
                        if (isset($_SESSION['user_id'])) {
                            echo "<td>
                                <a href='edit.php?id={$p['id']}' class='btn btn-sm btn-edit btn-custom'>
                                    <i class='bi bi-pencil-square'></i> Sửa
                                </a>
                                <a href='delete.php?id={$p['id']}' class='btn btn-sm btn-del btn-custom'
                                   onclick='return confirm(\"Bạn chắc chắn muốn xóa?\")'>
                                   <i class='bi bi-trash3'></i> Xóa
                                </a>
                            </td>";
                        }
                        echo "</tr>";
                    }
        ?>

            </tbody>
        </table>

        <?php
                } 
            } 
        } catch (Exception $e) {
            echo '<div class="alert alert-danger text-center">Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage() . '</div>';
        }
        ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>