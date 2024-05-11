<?php
include("../../config/database.php");
include("../../config/function.php");
require_once '../models/M_SanPham.php';

class SanPhamController
{
  private $SanPhamModel;
  private $conn;

  // Nhận một đối tượng kết nối CSDL ($conn) làm tham số
  // Sử dụng nó để khởi tạo đối tượng SanPhamModel, dùng để tương tác với CSDL.
  public function __construct($conn)
  {
    $this->conn = $conn;
    $this->SanPhamModel = new SanPhamModel($conn);
  }

  public function handleRequest()
  {
    // Kiểm tra phương thức HTTP mà người dùng yêu cầu có phải là POST hay không
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Xử lý thêm sản phẩm
      if (
        isset($_POST['masp']) && isset($_POST['tensp']) && isset($_POST['gia'])
        && isset($_FILES['hinhanh']) && isset($_POST['soluong']) && isset($_POST['maloai'])
      ) {
        $this->processInsert();
      }

      // Xử lý xóa sản phẩm
      elseif (isset($_POST['masp'])) {
        $this->processDelete();
      }

      // Xử lý cập nhật thông tin sản phẩm
      elseif (
        isset($_POST['masp_edit']) && isset($_POST['tensp_edit']) && isset($_POST['gia_edit'])
        && isset($_POST['soluong_edit']) && isset($_POST['maloai_edit'])
      ) {
        $this->processUpdate();
      }

      // Xử lý khi không tìm thấy action phù hợp
      else {
        $this->redirectWithAlert('Có lỗi xảy ra khi xử lý dữ liệu.', '../views/SanPham.php');
      }
    }
  }

  private function processInsert()
  {
    // Lấy dữ liệu từ form
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $gia = $_POST['gia'];
    $hinhanh = $_FILES['hinhanh'];
    $soluong = $_POST['soluong'];
    $maloai = $_POST['maloai'];

    // Lấy dữ liệu từ form cho mảng mausac và kichthuoc
    $mausac = isset($_POST['mausac']) ? $_POST['mausac'] : [];
    $kichthuoc = isset($_POST['kichthuoc']) ? $_POST['kichthuoc'] : [];

    $max_file_size = 3 * 1024 * 1024; // 3MB
    if ($hinhanh['size'] > $max_file_size) {
      redirect_with_alert('Kích thước ảnh quá lớn. Chọn kích thước ảnh nhỏ hơn 2MB', '../views/SanPham.php');
      exit();
    }

    $hinhanh_name = $hinhanh['name'];
    $hinhanh_tmp = $hinhanh['tmp_name'];

    // Kiểm tra định dạng ảnh (nếu cần)
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'webp');
    $file_extension = strtolower(pathinfo($hinhanh_name, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
      redirect_with_alert('Định dạng tập tin không được hỗ trợ. Vui lòng chọn tập tin ảnh có định dạng JPG, JPEG, PNG hoặc GIF.', '../views/SanPham.php');
      exit();
    }

    // Tạo tên file mới để tránh trùng lặp
    $new_filename = uniqid() . '.' . $file_extension;

    // Đường dẫn lưu file ảnh mới
    $hinhanh_path = "../../public/assets/images/" . $new_filename;

    // Di chuyển file ảnh từ tmp sang public/images
    move_uploaded_file($hinhanh_tmp, $hinhanh_path);

    // Tạo đối tượng SanPham
    $sanPham = new SanPham($masp, $tensp, $gia, $soluong, $new_filename, $maloai);

    // Gọi phương thức thêm sản phẩm từ model
    if ($this->SanPhamModel->themSanPham($sanPham, $mausac, $kichthuoc)) {
      $this->redirectWithAlert('Đã thêm sản phẩm thành công.', '../views/SanPham.php');
    } else {
      $this->redirectWithAlert('Sản phẩm đã tồn tại trong CSDL hoặc có lỗi xảy ra khi thêm.', '../views/SanPham.php');
    }
  }

  private function processDelete()
  {
    // Lấy dữ liệu từ form
    $masp = $_POST['masp'];

    // Gọi phương thức xóa sản phẩm từ model
    if ($this->SanPhamModel->xoaSanPham($masp)) {
      $this->redirectWithAlert('Xoá sản phẩm thành công.', '../views/SanPham.php');
    } else {
      $this->redirectWithAlert('Không thể xóa sản phẩm vì đã có trong đơn đặt hàng hoặc có lỗi xảy ra.', '../../sanpham.php');
    }
  }

  private function processUpdate()
  {
    // Lấy dữ liệu từ form
    $masp = $_POST['masp_edit'];
    $tensp = $_POST['tensp_edit'];
    $gia = $_POST['gia_edit'];
    $soluong = $_POST['soluong_edit'];
    $maloai = $_POST['maloai_edit'];

    // Lấy dữ liệu từ form cho mảng mausac_edit và kichthuoc_edit
    $mausac_edit = isset($_POST['mausac_edit']) ? $_POST['mausac_edit'] : [];
    $kichthuoc_edit = isset($_POST['kichthuoc_edit']) ? $_POST['kichthuoc_edit'] : [];

    $newImage = false;

    // Kiểm tra nếu có file ảnh mới được chọn
    if (!empty($_FILES['hinhanh_edit']['name'])) {
      $newImage = true;

      $hinhanh = $_FILES['hinhanh_edit'];

      // Kiểm tra kích thước file
      $max_file_size = 3 * 1024 * 1024; // 3MB
      if ($hinhanh['size'] > $max_file_size) {
        redirect_with_alert('Kích thước ảnh quá lớn. Chọn kích thước ảnh nhỏ hơn 2MB', '../views/SanPham.php');
        exit();
      }

      // Kiểm tra định dạng ảnh
      $allowed_extensions = array('jpg', 'jpeg', 'png', 'webp');
      $file_extension = strtolower(pathinfo($hinhanh['name'], PATHINFO_EXTENSION));
      if (!in_array($file_extension, $allowed_extensions)) {
        redirect_with_alert('Định dạng tập tin không được hỗ trợ. Vui lòng chọn tập tin ảnh có định dạng JPG, JPEG, PNG hoặc GIF.', '../views/SanPham.php');
        exit();
      }

      $hinhanh_name = $hinhanh['name'];
      $hinhanh_tmp = $hinhanh['tmp_name'];
      $new_filename = uniqid() . '.' . $file_extension;
      $hinhanh_path = "../../public/assets/images/" . $new_filename;
      move_uploaded_file($hinhanh_tmp, $hinhanh_path);
    }
    
    // Tạo đối tượng SanPham
    if ($newImage) {
      $sanPham = new SanPham($masp, $tensp, $gia, $soluong, $new_filename, $maloai);
    } else {
      $sanPham = new SanPham($masp, $tensp, $gia, $soluong, null, $maloai);
    }

    // Gọi phương thức cập nhật thông tin sản phẩm từ model
    if ($this->SanPhamModel->capNhatSanPham($sanPham, $mausac_edit, $kichthuoc_edit)) {
      $this->redirectWithAlert('Cập nhật thông tin sản phẩm thành công.', '../views/SanPham.php');
    } else {
      $this->redirectWithAlert('Có lỗi xảy ra khi cập nhật thông tin sản phẩm.', '../views/SanPham.php');
    }
  }

  private function redirectWithAlert($message, $location)
  {
    redirect_with_alert($message, $location);
    exit();
  }
}

// Khởi tạo đối tượng SanPhamController và gọi hàm xử lý request khi controller được gọi
$SanPhamController = new SanPhamController($conn);
$SanPhamController->handleRequest();
