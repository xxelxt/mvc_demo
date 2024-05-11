<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $quyen = isset($_SESSION['quyen']) ? $_SESSION['quyen'] : '';

    function isActive($page) {
        $current = basename($_SERVER['PHP_SELF']);
        if ($current === $page . '.php') {
            echo 'active';
        }
    }

?>

<div class="d-flex justify-content-between align-items-center">
    <div class="d-flex">
        <?php if ($quyen == 1) { ?>
            <div class="dropdown" style="margin: 5px 5px;">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                    <li><a class="dropdown-item <?php isActive('LoaiSanPham'); ?>" href="LoaiSanPham.php">Loại sản
                            phẩm</a></li>
                    <li><a class="dropdown-item <?php isActive('MauSac'); ?>" href="MauSac.php">Màu sắc</a></li>
                    <li><a class="dropdown-item <?php isActive('KichThuoc'); ?>" href="KichThuoc.php">Kích thước</a>
                    </li>
                    <li><a class="dropdown-item <?php isActive('TaiKhoan'); ?>" href="TaiKhoan.php">Tài khoản</a></li>

                </ul>
            </div>
        <?php } ?>

        <div class="header-menu">
            <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a class="<?php echo isActive('SanPham'); ?>" href="SanPham.php">Sản phẩm</a></li>
                <li><a href="#">Về chúng tôi</a></li>
            </ul>
        </div>
    </div>

    <!-- Đăng nhập hoặc tên người dùng -->
    <div class="d-flex align-items-center">
        <?php
            if (isset($_SESSION['user'])) { ?>
                <!-- Hiển thị tên người dùng và dropdown menu -->
                <div class="dropdown">
                    <button class="btn dropdown-toggle me-2" type="button" id="userDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <?php echo $_SESSION['user']; ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../controllers/C_AuthProcess.php?action=logout">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            <?php } else { ?>
                <!-- Hiển thị nút đăng nhập -->
                <button type="button" class="btn" onclick="location.href='Login.php';">Đăng nhập</button>
            <?php } ?>
    </div>
</div>

<style>
    .nav-link {
        border: 1px solid;
        color: black;
    }

    .header-menu {
        background-color: #ffffff;
        /* Màu nền của header */
        padding: 10px;
    }

    .header-menu ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .header-menu li {
        display: inline;
        margin-right: 20px;
    }

    .header-menu a {
        color: black;
        text-decoration: none;
        padding: 10px;
    }
</style>