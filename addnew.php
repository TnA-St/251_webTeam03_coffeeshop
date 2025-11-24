<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $cat   = $_POST['category_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    if (!empty($name) && $price >= 0) {
        $sql = "INSERT INTO products (name, category_name, price, stock) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $cat, $price, $stock]);
        
        $_SESSION['msg'] = "Đã thêm món mới '$name' thành công!";
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Món Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .navbar { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.8)!important; }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .form-label { font-weight: bold; color: #495057; }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6c5ce7;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.15);
        }

        .btn-save {
            background: linear-gradient(135deg, #0984e3, #6c5ce7);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
            color: white;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="glass-panel">
                
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark"><i class="bi bi-plus-circle-fill text-primary"></i> Thêm Món Mới</h3>
                    <p class="text-muted">Nhập thông tin sản phẩm vào kho</p>
                </div>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-tag-fill me-1"></i> Tên món</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ví dụ: Cà phê sữa đá...">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-bookmark-fill me-1"></i> Danh mục</label>
                            <select name="category_name" class="form-select">
                                <option>Cà phê</option>
                                <option>Trà & Sinh tố</option>
                                <option>Đồ ăn nhẹ</option>
                                <option>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-currency-dollar me-1"></i> Giá bán</label>
                            <input type="number" name="price" class="form-control" required min="0" placeholder="0">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"><i class="bi bi-box-seam-fill me-1"></i> Tồn kho ban đầu</label>
                        <input type="number" name="stock" class="form-control" value="50" min="0">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Lưu Sản Phẩm
                        </button>
                        <a href="index.php" class="btn btn-light border text-secondary py-2 rounded-3 fw-bold mt-1">
                            <i class="bi bi-arrow-return-left me-2"></i> Hủy bỏ
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
