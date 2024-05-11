<?php
    include("../../config/database.php");
    include("../../config/function.php");
    require_once '../models/M_KichThuoc.php';

    class KichThuocController {
        private $KichThuocModel;

        // Nhận một đối tượng kết nối CSDL ($conn) làm tham số
        // Sử dụng nó để khởi tạo đối tượng KichThuocModel, dùng để tương tác với CSDL.
        public function __construct($conn) {
            $this -> KichThuocModel = new KichThuocModel($conn);
        }

        public function handleRequest() {
            // Kiểm tra phương thức HTTP mà người dùng yêu cầu có phải là POST hay không
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Nếu có mã kích thước và tên kích thước được gửi lên thì xử lý thêm kích thước
                if (isset($_POST['makt']) && isset($_POST['tenkt'])) {
                    $this -> processInsert();
                } // Nếu có mã kích thước (edit) và tên kích thước (edit) được gửi lên thì xử lý cập nhật kích thước
                elseif (isset($_POST['makt_edit']) && isset($_POST['tenkt_edit'])) {
                    $this -> processUpdate();
                } // Nếu có mã kích thước được gửi lên thì xử lý xóa kích thước
                elseif (isset($_POST['makt'])) {
                    $this -> processDelete();
                } // Xử lý khi không tìm thấy action phù hợp
                else {
                    $this -> redirectWithAlert('Có lỗi xảy ra khi xử lý dữ liệu.', '../views/LoaiSanPham.php');
                }
            }
        }

        private function processInsert() {
            // Lấy dữ liệu từ form
            $makt = $_POST['makt'];
            $tenkt = $_POST['tenkt'];

            // Gọi tới hàm thêm kích thước từ Model để thêm kích thước vào CSDL
            if ($this -> KichThuocModel -> themKT($makt, $tenkt)) {
                $this -> redirectWithAlert('Đã thêm thành công.', '../views/KichThuoc.php');
            } else {
                $this -> redirectWithAlert('Mã kích thước đã tồn tại trong CSDL hoặc có lỗi xảy ra khi thêm.', '../views/KichThuoc.php');
            }
        }

        private function processUpdate() {
            // Lấy dữ liệu từ form
            $makt = $_POST['makt_edit'];
            $tenkt = $_POST['tenkt_edit'];

            // Gọi tới hàm cập nhật kích thước từ Model để cập nhật thông tin kích thước
            if ($this -> KichThuocModel -> capNhatKT($makt, $tenkt)) {
                $this -> redirectWithAlert('Cập nhật thông tin thành công.', '../views/KichThuoc.php');
            } else {
                $this -> redirectWithAlert('Lỗi: Không thể cập nhật thông tin kích thước.', '../views/KichThuoc.php');
            }
        }

        private function processDelete() {
            // Lấy dữ liệu từ form
            $makt = $_POST['makt'];

            // Gọi tới hàm xóa kích thước từ Model để xóa kích thước
            if ($this -> KichThuocModel -> xoaKT($makt)) {
                $this -> redirectWithAlert('Xoá thành công.', '../views/KichThuoc.php');
            } else {
                $this -> redirectWithAlert('Không thể xóa kích thước vì còn sản phẩm thuộc kích thước đó hoặc có lỗi xảy ra.', '../views/KichThuoc.php');
            }
        }

        private function redirectWithAlert($message, $location) {
            redirect_with_alert($message, $location);
            exit();
        }
    }

    global $conn;

    // Khởi tạo đối tượng KichThuocController và gọi hàm xử lý request khi controller được gọi
    $KichThuocController = new KichThuocController($conn);
    $KichThuocController -> handleRequest();