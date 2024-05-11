<?php
include("../../config/database.php");
include("../../config/function.php");
require_once '../models/M_MauSac.php';

class MauSacController
{
  private $MauSacModel;

  // Nhận một đối tượng kết nối CSDL ($conn) làm tham số
  // Sử dụng nó để khởi tạo đối tượng MauSacModel, dùng để tương tác với CSDL.
  public function __construct($conn)
  {
    $this->MauSacModel = new MauSacModel($conn);
  }

  public function handleRequest()
  {
    // Kiểm tra phương thức HTTP mà người dùng yêu cầu có phải là POST hay không
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Nếu có mã màu và tên màu được gửi lên thì xử lý thêm màu sắc
      if (isset($_POST['mamau']) && isset($_POST['tenmau'])) {
        $this->processInsert();
      }

      // Nếu có mã màu (edit) và tên màu (edit) được gửi lên thì xử lý cập nhật màu sắc
      elseif (isset($_POST['mamau_edit']) && isset($_POST['tenmau_edit'])) {
        $this->processUpdate();
      }

      // Nếu có mã màu được gửi lên thì xử lý xóa màu sắc
      elseif (isset($_POST['mamau'])) {
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
    $mamau = $_POST['mamau'];
    $tenmau = $_POST['tenmau'];

    // Gọi tới hàm thêm màu sắc từ Model để thêm màu sắc vào CSDL
    if ($this->MauSacModel->themMauSac($mamau, $tenmau)) {
      $this->redirectWithAlert('Đã thêm thành công.', '../views/MauSac.php');
    } else {
      $this->redirectWithAlert('Mã màu đã tồn tại trong CSDL hoặc có lỗi xảy ra khi thêm.', '../views/MauSac.php');
    }
  }

  private function processUpdate()
  {
    // Lấy dữ liệu từ form
    $mamau = $_POST['mamau_edit'];
    $tenmau = $_POST['tenmau_edit'];

    // Gọi tới hàm cập nhật màu sắc từ Model để cập nhật thông tin màu sắc
    if ($this->MauSacModel->capNhatMauSac($mamau, $tenmau)) {
      $this->redirectWithAlert('Cập nhật thông tin thành công.', '../views/MauSac.php');
    } else {
      $this->redirectWithAlert('Lỗi: Không thể cập nhật thông tin màu sắc.', '../views/MauSac.php');
    }
  }

  private function processDelete()
  {
    // Lấy dữ liệu từ form
    $mamau = $_POST['mamau'];

    // Gọi tới hàm xóa màu sắc từ Model để xóa màu sắc khỏi CSDL
    if ($this->MauSacModel->xoaMauSac($mamau)) {
      $this->redirectWithAlert('Xoá thành công.', '../views/MauSac.php');
    } else {
      $this->redirectWithAlert('Không thể xóa màu sắc vì còn sản phẩm thuộc màu đó hoặc có lỗi xảy ra.', '../views/MauSac.php');
    }
  }

  private function redirectWithAlert($message, $location)
  {
    redirect_with_alert($message, $location);
    exit();
  }
}

// Khởi tạo đối tượng Controller và gọi hàm xử lý request khi controller được gọi
$MauSacController = new MauSacController($conn);
$MauSacController->handleRequest();