<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    @keyframes slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes shakeIcon {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(-10deg); }
        50% { transform: rotate(10deg); }
        75% { transform: rotate(-5deg); }
        100% { transform: rotate(0deg); }
    }
    @keyframes bounceUp {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    .navbar-animated {
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; 
    }
    .brand-animate {
        transition: all 0.3s ease;
    }
    .brand-animate:hover {
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        transform: scale(1.02);
    }
    .brand-animate:hover i {
        display: inline-block;
        animation: shakeIcon 0.5s ease;
    }
    .nav-link-animate {
        color: rgba(255, 255, 255, 0.7) !important;
        font-weight: 500;
        padding: 8px 16px !important;
        margin: 0 5px;
        position: relative;
        transition: all 0.3s ease;
        border-radius: 8px; 
    }
    .nav-link-animate i {
        display: inline-block;
        transition: transform 0.3s;
    }

    .nav-link-animate:hover {
        color: #fff !important;
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-link-animate:hover i {
        animation: bounceUp 0.4s ease;
    }
    .nav-link-animate::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 5px;
        left: 50%;
        background: #0984e3; 
        transition: width 0.3s ease, left 0.3s ease;
        opacity: 0;
    }

    .nav-link-animate:hover::after {
        width: 80%;
        left: 10%;
        opacity: 1;
    }
    .nav-link-animate.active {
        color: #fff !important;
        background: linear-gradient(135deg, #0984e3, #6c5ce7);
        box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4);
        font-weight: bold;
    }
    .nav-link-animate.active::after {
        display: none; 
    }
    .btn-logout-animate {
        transition: all 0.3s ease;
        border: 1px solid rgba(220, 53, 69, 0.6);
    }
    .btn-logout-animate:hover {
        background-color: #dc3545;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-animated">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold brand-animate" href="index.php">
            <i class="bi bi-cup-hot-fill text-warning"></i> Quản Lý Quán Cafe
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-3">
                <a class="nav-link nav-link-animate <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">
                    <i class="bi bi-box-seam me-1"></i> Sản phẩm
                </a>
                <a class="nav-link nav-link-animate <?= ($current_page == 'pos.php') ? 'active' : '' ?>" href="pos.php">
                    <i class="bi bi-cart4 me-1"></i> Bán Hàng
                </a>
                <a class="nav-link nav-link-animate <?= ($current_page == 'orders.php') ? 'active' : '' ?>" href="orders.php">
                    <i class="bi bi-receipt me-1"></i> Đơn Hàng
                </a>
                <a class="nav-link nav-link-animate <?= ($current_page == 'user.php') ? 'active' : '' ?>" href="user.php">
                    <i class="bi bi-person-lines-fill me-1"></i> Tài Khoảng
                </a>
            </div>
            
            <div class="d-flex align-items-center text-white ms-auto">
                <div class="me-3 text-end d-none d-lg-block border-end pe-3 border-secondary border-opacity-50">
                    <small class="text-white-50" style="font-size: 0.8rem;">Xin chào,</small><br>
                    <b class="text-light"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></b>
                </div>
                <a href="logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold btn-logout-animate">
                    <i class="bi bi-box-arrow-right"></i> Thoát
                </a>
            </div>
        </div>
    </div>
</nav>
```