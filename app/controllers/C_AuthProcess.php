<?php
include('../../config/database.php');
require_once 'C_Auth.php';

// Lớp AuthController xử lý các yêu cầu liên quan đến đăng nhập và đăng xuất

// Lấy action từ phương thức GET
$action = isset($_GET['action']) ? $_GET['action'] : '';
$authController = new AuthController($conn);

// Xử lý yêu cầu đăng nhập
if ($action === 'login' && isset($_POST['username']) && isset($_POST['password'])) {
  // Lấy dữ liệu từ form
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Bắt đầu session
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  // Gọi phương thức login từ AuthController để kiểm tra thông tin đăng nhập
  $user = $authController->login($username, $password);

  // Nếu thông tin đăng nhập hợp lệ thì set session và chuyển hướng
  if ($user) {
    $_SESSION['user'] = $username;
    $_SESSION['quyen'] = $user['priv'];

    header('Location: ../views/SanPham.php');
    exit();
  } else {
    header('Location: ../views/Login.php?error=1');
    exit();
  }
// Xử lý yêu cầu đăng xuất
} elseif ($action === 'logout') {
  $authController->logout();
  exit();
}
