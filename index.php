<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(40px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* CSS Navbar ghi đè */
        .navbar { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.8)!important; padding: 8px 16px!important; border-radius: 30px; transition: 0.3s; margin: 0 5px; }
        .nav-link:hover, .nav-link.active { background: linear-gradient(45deg, #0984e3, #6c5ce7); color: #fff!important; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4); }

        /* Glass Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 50px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            animation: slideUpFade 0.6s ease forwards;
        }

        /* Table Container */
        .table-rounded-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
        }

        .table-hover tbody tr:hover { background-color: rgba(108, 92, 231, 0.05); transition: 0.2s; }
        .badge-cat { background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; }
        .price-tag { color: #d63031; font-weight: bold; font-family: monospace; font-size: 1.1em; }
        
        .btn-add {
            background: linear-gradient(135deg, #00b894, #0984e3); border: none; color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); transition: 0.3s;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); color: white; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="glass-panel">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 border-light border-opacity-25">
            <h3 class="m-0 fw-bold text-white"><i class="bi bi-box-seam-fill"></i> Quản Lý Sản Phẩm</h3>
            <a href="addnew.php" class="btn btn-add rounded-pill px-4 py-2 fw-bold">
                <i class="bi bi-plus-circle"></i> Thêm món mới
            </a>
        </div>

        <?php
        $stmt = $pdo->query("SELECT * FROM products ORDER BY category_name ASC, name ASC");
        $products = $stmt->fetchAll();
        ?>

        <div class="table-rounded-container">
            <table class="table table-hover align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th class="py-3 ps-4" width="5%">#</th>
                        <th class="py-3" width="30%">Tên món</th>
                        <th class="py-3" width="20%">Danh mục</th>
                        <th class="py-3" width="15%">Giá bán</th>
                        <th class="py-3" width="15%">Tồn kho</th>
                        <th class="py-3 text-center" width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $index => $p): ?>
                        <tr>
                            <td class="text-muted ps-4"><?= $index + 1 ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($p['name']) ?></td>
                            <td><span class="badge-cat"><?= htmlspecialchars($p['category_name']) ?></span></td>
                            <td class="price-tag"><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
                            <td>
                                <?php if($p['stock'] <= 10): ?>
                                    <span class="text-danger fw-bold bg-danger bg-opacity-10 px-2 py-1 rounded">
                                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $p['stock'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-success fw-bold"><?= $p['stock'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle shadow-sm" title="Sửa">
                                    <i class="bi bi-pencil-square fs-6"></i>
                                </a>
                                <a href="delete.php?id=<?= $p['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger border-0 rounded-circle shadow-sm ms-1" 
                                   onclick="return confirm('Xóa món \'<?= htmlspecialchars($p['name']) ?>\'?')"
                                   title="Xóa">
                                    <i class="bi bi-trash3-fill fs-6"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-1 opacity-25"></i><br>
                                <span class="mt-3 d-block">Kho đang trống. Hãy thêm món mới!</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>