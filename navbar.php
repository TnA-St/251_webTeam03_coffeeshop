<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-cup-hot-fill"></i> Cafe Manager
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-3">
                <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">
                    <i class="bi bi-box-seam"></i> Sản phẩm
                </a>
                <a class="nav-link <?= ($current_page == 'pos.php') ? 'active' : '' ?>" href="pos.php">
                    <i class="bi bi-cart4"></i> Bán Hàng
                </a>
                <a class="nav-link <?= ($current_page == 'orders.php') ? 'active' : '' ?>" href="orders.php">
                    <i class="bi bi-receipt"></i> Đơn Hàng
                </a>
            </div>
            
            <div class="d-flex align-items-center text-white ms-auto">
                <div class="me-3 text-end d-none d-lg-block border-end pe-3 border-secondary border-opacity-50">
                    <small class="text-white-50" style="font-size: 0.8rem;">Xin chào,</small><br>
                    <b class="text-light"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></b>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm border-2 fw-bold">
                    <i class="bi bi-box-arrow-right"></i> Thoát
                </a>
            </div>
        </div>
    </div>
</nav>