<?php

require_once 'E_LoaiSanPham.php';

class LoaiSanPhamModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // Hàm lấy danh sách loại sản phẩm từ CSDL
  public function layDanhSach($recordPerPage, $offset, $keyword = null)
  {
    // Tạo mảng chứa danh sách loại sản phẩm
    $dsLoaiSanPham = [];

    $sql = "SELECT * FROM loaisanpham";

    // Nếu có từ khóa tìm kiếm thì thêm điều kiện WHERE vào string sql
    if ($keyword !== null) {
      $sql .= " WHERE tenloai LIKE '%$keyword%'";
    }

    // Thêm string LIMIT vào câu truy vấn để phân trang
    $sql .= " LIMIT $offset, $recordPerPage";

    $result = $this->conn->query($sql);

    // Lặp qua kết quả truy vấn được từ fetch_assoc để lấy dữ liệu, tạo đối tượng LoaiSanPham và thêm vào mảng
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $loaiSanPham = new LoaiSanPham($row['maloai'], $row['tenloai']);
        $dsLoaiSanPham[] = $loaiSanPham;
      }
    }

    // Tính tổng số bản ghi
    $sqlTotalRecords = "SELECT COUNT(*) AS total FROM loaisanpham";

    if ($keyword !== null) {
      $sqlTotalRecords .= " WHERE tenloai LIKE '%$keyword%'";
    }

    $resultTotalRecords = $this->conn->query($sqlTotalRecords);
    $totalRecords = $resultTotalRecords->fetch_assoc()['total'];

    return [$dsLoaiSanPham, $totalRecords];
  }

  // Lấy tên loại từ mã loại
  public function layTenLoai($maloai)
  {
    $sql = "SELECT tenloai FROM loaisanpham WHERE maloai = '$maloai'";
    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['tenloai'];
  }

  public function layMaLoai()
  {
    $sql = "SELECT maloai FROM loaisanpham";
    $result = $this->conn->query($sql);
    $dsMaLoai = [];
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $dsMaLoai[] = $row['maloai'];
      }
    }
    return $dsMaLoai;
  }

  public function themLoaiSanPham($maloai, $tenloai)
  {
    // Kiểm tra xem mã loại đã tồn tại chưa
    $check_sql = "SELECT COUNT(*) AS total FROM loaisanpham WHERE maloai = '$maloai'";
    $check_result = $this->conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    $total_records = $check_row['total'];

    // Nếu tồn tại thì dừng việc thêm
    if ($total_records > 0) {
      return false;
    }

    $sql = "INSERT INTO loaisanpham (maloai, tenloai) VALUES ('$maloai', '$tenloai')";

    // Thực thi câu truy vấn, nếu thành công thì trả về true, ngược lại trả về false
    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  public function xoaLoaiSanPham($maloai)
  {
    // Kiểm tra xem có sản phẩm nào trong CSDL thuộc loại này không
    $sql_check_sp = "SELECT COUNT(*) as count FROM sanpham WHERE maloai = '$maloai'";
    $result_check_sp = $this->conn->query($sql_check_sp);
    $row_check_sp = $result_check_sp->fetch_assoc();

    if ($row_check_sp['count'] > 0) {
      return false;
    }

    $sql = "DELETE FROM loaisanpham WHERE maloai = '$maloai'";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  public function capNhatLoaiSanPham($maloai, $tenloai)
  {
    $sql = "UPDATE loaisanpham SET tenloai = '$tenloai' WHERE maloai = '$maloai'";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }
}
