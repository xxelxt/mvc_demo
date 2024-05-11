<?php
include("../../config/database.php");
include("../../config/function.php");
require_once '../models/M_LoaiSanPham.php';

class LoaiSanPhamController
{
  private $LoaiSanPhamModel;

  // Nhận một đối tượng kết nối CSDL ($conn) làm tham số
  // Sử dụng nó để khởi tạo đối tượng LoaiSanPhamModel, dùng để tương tác với CSDL.
  public function __construct($conn)
  {
    $this->LoaiSanPhamModel = new LoaiSanPhamModel($conn);
  }

  public function handleRequest()
  {
    // Kiểm tra phương thức HTTP mà người dùng yêu cầu có phải là POST hay không
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Nếu có mã loại và tên loại được gửi lên thì xử lý thêm loại
      if (isset($_POST['maloai']) && isset($_POST['tenloai'])) {
        $this->processInsert();
      }

      // Nếu có mã loại (edit) và tên loại (edit) được gửi lên thì xử lý cập nhật loại
      elseif (isset($_POST['maloai_edit']) && isset($_POST['tenloai_edit'])) {
        $this->processUpdate();
      }

      // Nếu có mã loại được gửi lên thì xử lý xóa loại
      elseif (isset($_POST['maloai'])) {
        $this->processDelete();
      }

      // Xử lý khi không tìm thấy action phù hợp
      else {
        $this->redirectWithAlert('Có lỗi xảy ra khi xử lý dữ liệu.', '../views/LoaiSanPham.php');
      }
    }
  }

  private function processInsert()
  {
    // Lấy dữ liệu từ form
    $maloai = $_POST['maloai'];
    $tenloai = $_POST['tenloai'];

    // Gọi tới hàm thêm loại sản phẩm từ Model để thêm vào CSDL
    if ($this->LoaiSanPhamModel->themLoaiSanPham($maloai, $tenloai)) {
      $this->redirectWithAlert('Đã thêm thành công.', '../views/LoaiSanPham.php');
    } else {
      $this->redirectWithAlert('Mã loại hàng đã tồn tại trong CSDL hoặc có lỗi xảy ra khi thêm.', '../views/LoaiSanPham.php');
    }
  }

  private function processUpdate()
  {
    // Lấy dữ liệu từ form
    $maloai = $_POST['maloai_edit'];
    $tenloai = $_POST['tenloai_edit'];

    // Gọi tới hàm cập nhật loại sản phẩm từ Model để cập nhật vào CSDL
    if ($this->LoaiSanPhamModel->capNhatLoaiSanPham($maloai, $tenloai)) {
      $this->redirectWithAlert('Cập nhật thông tin thành công.', '../views/LoaiSanPham.php');
    } else {
      $this->redirectWithAlert('Lỗi: Không thể cập nhật thông tin loại sản phẩm.', '../views/LoaiSanPham.php');
    }
  }

  private function processDelete()
  {
    // Lấy mã loại từ form
    $maloai = $_POST['maloai'];

    // Gọi tới hàm xóa loại sản phẩm từ Model để xóa khỏi CSDL
    if ($this->LoaiSanPhamModel->xoaLoaiSanPham($maloai)) {
      $this->redirectWithAlert('Xoá thành công.', '../views/LoaiSanPham.php');
    } else {
      $this->redirectWithAlert('Không thể xóa loại hàng vì còn sản phẩm thuộc loại đó hoặc có lỗi xảy ra.', '../views/LoaiSanPham.php');
    }
  }

  private function redirectWithAlert($message, $location)
  {
    redirect_with_alert($message, $location);
    exit();
  }
}

// Khởi tạo đối tượng LoaiSanPhamController và gọi hàm xử lý request từ người dùng
$LoaiSanPhamController = new LoaiSanPhamController($conn);
$LoaiSanPhamController->handleRequest();
