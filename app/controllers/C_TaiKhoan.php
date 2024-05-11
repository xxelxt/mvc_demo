<?php
    include("../../config/database.php");
    include("../../config/function.php");
    require_once '../models/M_TaiKhoan.php';

    class TaiKhoanController {
        private $TaiKhoanModel;

        // Nhận một đối tượng kết nối CSDL ($conn) làm tham số
        // Sử dụng nó để khởi tạo đối tượng TaiKhoanModel, dùng để tương tác với CSDL.
        public function __construct($conn) {
            $this -> TaiKhoanModel = new TaiKhoanModel($conn);
        }

        public function handleRequest() {
            // Kiểm tra phương thức HTTP mà người dùng yêu cầu có phải là POST hay không
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Xử lý thêm tài khoản
                if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['priv'])) {
                    $this -> processInsert();
                } // Xử lý cập nhật thông tin tài khoản
                elseif (isset($_POST['username_edit']) && isset($_POST['password_edit']) && isset($_POST['priv_edit'])) {
                    $this -> processUpdate();
                } // Xử lý xóa tài khoản
                elseif (isset($_POST['username'])) {
                    $this -> processDelete();
                } // Xử lý khi không tìm thấy action phù hợp
                else {
                    $this -> redirectWithAlert('Có lỗi xảy ra khi xử lý dữ liệu.', '../views/TaiKhoan.php');
                }
            }
        }

        private function processInsert() {
            // Lấy dữ liệu từ form
            $username = $_POST['username'];
            $password = $_POST['password'];
            $priv = $_POST['priv'];

            // Gọi phương thức thêm tài khoản từ model
            if ($this -> TaiKhoanModel -> themTaiKhoan($username, $password, $priv)) {
                $this -> redirectWithAlert('Đã thêm thành công.', '../views/TaiKhoan.php');
            } else {
                $this -> redirectWithAlert('Tài khoản đã tồn tại trong CSDL hoặc có lỗi xảy ra khi thêm.', '../views/TaiKhoan.php');
            }
        }

        private function processUpdate() {
            // Lấy dữ liệu từ form
            $username = $_POST['username_edit'];
            $password = $_POST['password_edit'];
            $priv = $_POST['priv_edit'];

            // Gọi phương thức cập nhật tài khoản từ model
            if ($this -> TaiKhoanModel -> capNhatTaiKhoan($username, $password, $priv)) {
                $this -> redirectWithAlert('Cập nhật thông tin thành công.', '../views/TaiKhoan.php');
            } else {
                $this -> redirectWithAlert('Có lỗi xảy ra khi cập nhật thông tin tài khoản.', '../views/TaiKhoan.php');
            }
        }

        private function processDelete() {
            // Lấy dữ liệu từ form
            $username = $_POST['username'];

            // Gọi phương thức xóa tài khoản từ model
            if ($this -> TaiKhoanModel -> xoaTaiKhoan($username)) {
                $this -> redirectWithAlert('Xoá thành công.', '../views/TaiKhoan.php');
            } else {
                $this -> redirectWithAlert('Có lỗi xảy ra khi xoá tài khoản.', '');
            }
        }

        private function redirectWithAlert($message, $location) {
            redirect_with_alert($message, $location);
            exit();
        }
    }

    global $conn;

    // Khởi tạo đối tượng TaiKhoanController và gọi hàm xử lý request khi controller được gọi
    $TaiKhoanController = new TaiKhoanController($conn);
    $TaiKhoanController -> handleRequest();
