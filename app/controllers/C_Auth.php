<?php
    require_once '../models/M_TaiKhoan.php';

    class AuthController {
        private $userModel;

        // Nhận một đối tượng kết nối CSDL ($conn) làm tham số
        // Sử dụng nó để khởi tạo đối tượng TaiKhoanModel, dùng để tương tác với CSDL.
        public function __construct($conn) {
            $this -> userModel = new TaiKhoanModel($conn);
        }

        // Xử lý đăng nhập
        public function login($username, $password) {
            return $this -> userModel -> authenticate($username, $password);
        }

        // Xử lý đăng xuất
        public function logout() {
            session_start();
            session_destroy();
            header('Location: ../views/Login.php');
        }
    }