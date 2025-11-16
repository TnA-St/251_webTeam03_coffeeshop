<?php
require_once __DIR__ . '/config.php';
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
            background-image: url('background-image.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-color: #2d3436;
            font-family: 'Segoe UI', sans-serif;
            padding-bottom: 50px;
        }

        /* === ĐÃ THAY ĐỔI: NAVBAR ÁP DỤNG GLASS UI === */
        .navbar {
            /* 1. Nền mờ (Dark Glass) */
            background: rgba(45, 52, 54, 0.7); /* Màu gốc #2d3436 nhưng mờ hơn */
            
            /* 2. Hiệu ứng kính mờ */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px); /* Hỗ trợ Safari */
            
            /* 3. Viền bắt sáng */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);

            /* 4. Bóng đổ */
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            color: #fff !important;
            font-weight: 600;
        }

        /* === ĐÃ THAY ĐỔI: CONTENT BOX ÁP DỤNG GLASS UI === */
        .container-box {
             /* 1. Nền mờ (Light Glass) */
            background: rgba(255, 255, 255, 0.6); /* Mờ hơn để thấy nền */
            
            /* 2. Hiệu ứng kính mờ */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px); /* Hỗ trợ Safari */

            /* 3. Viền bắt sáng */
            border: 1px solid rgba(255, 255, 255, 0.3);
            
            /* 4. Bóng đổ */
            box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.15);
            
            /* Các style cũ */
            padding: 30px;
            border-radius: 14px;
            margin-top: 30px;
        }

        /* Style cho bảng để hợp với Glass UI */
        .table thead {
            background: #2d3436;
            color: #fff;
        }
        .table.table-bordered {
             /* Làm viền bảng tinh tế hơn */
            border-color: rgba(0, 0, 0, 0.15);
        }
        .table-hover > tbody > tr:hover > * {
            /* Hiệu ứng hover cho bảng */
            background-color: rgba(255, 255, 255, 0.3);
            color: #000;
        }

        /* Giữ nguyên style các nút bấm */
        .btn-custom {
            border-radius: 8px;
            padding: 8px 14px;
            transition: all 0.3s ease;
            border: none; /* Xóa viền mặc định để đẹp hơn */
        }
        .btn-add {
            background: #0984e3;
            color: #fff;
        }
        .btn-add:hover {
            background: #74b9ff;
            color: #000;
        }
        .btn-edit {
            background: #fdcb6e;
            color: #000;
        }
        .btn-edit:hover {
            background: #ffeaa7;
        }
        .btn-del {
            background: #d63031;
            color: #fff;
        }
        .btn-del:hover {
            background: #ff7675;
            color: #000;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            ☕ Quản Lý Cà Phê
        </a>
    </div>
</nav>

<div class="container">
    <div class="container-box">

        <h3 class="mb-4 text-center">📦 DANH SÁCH SẢN PHẨM</h3>

        <div class="text-end mb-3">
            <a href="addnew.php" class="btn btn-custom btn-add">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm
            </a>
        </div>

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
                    <th width="160">Hành động</th>
                </tr>
            </thead>
            <tbody>

        <?php
                    foreach ($products as $p) {
                        echo "
                        <tr>
                            <td>{$p['id']}</td>
                            <td>{$p['name']}</td>
                            <td>" . number_format($p['price'], 0) . " đ</td>
                            <td>{$p['stock']}</td>
                            <td>
                                <a href='edit.php?id={$p['id']}' class='btn btn-sm btn-edit btn-custom'>
                                    <i class='bi bi-pencil-square'></i> Sửa
                                </a>
                                <a href='delete.php?id={$p['id']}' class='btn btn-sm btn-del btn-custom'
                                   onclick='return confirm(\"Bạn chắc chắn muốn xóa?\")'>
                                   <i class='bi bi-trash3'></i> Xóa
                                </a>
                            </td>
                        </tr>";
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

</body>
</html>