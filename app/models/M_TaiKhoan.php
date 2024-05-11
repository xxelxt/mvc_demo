<?php

require_once 'E_TaiKhoan.php';

class TaiKhoanModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // Hàm xác thực thông tin đăng nhập mà người dùng nhập vào
  public function authenticate($username, $password)
  {
    $sql = "SELECT * FROM taikhoan WHERE username = '$username' AND password = '$password'";
    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      return $result->fetch_assoc();
    } else {
      return false;
    }
  }

  // Hàm lấy danh sách tài khoản từ CSDL
  public function layDanhSach($recordPerPage, $offset, $keyword = null)
  {
    // Tạo mảng chứa danh sách tài khoản
    $dsTaiKhoan = [];

    $sql = "SELECT * FROM taikhoan";

    // Nếu có từ khóa tìm kiếm thì thêm điều kiện WHERE vào string sql
    if ($keyword !== null) {
      $sql .= " WHERE username LIKE '%$keyword%'";
    }

    // Thêm string LIMIT vào câu truy vấn để phân trang
    $sql .= " LIMIT $offset, $recordPerPage";

    $result = $this->conn->query($sql);

    // Lặp qua kết quả truy vấn được từ fetch_assoc để lấy dữ liệu, tạo đối tượng TaiKhoan và thêm vào mảng
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $taiKhoan = new TaiKhoan($row['username'], $row['password'], $row['priv']);
        $dsTaiKhoan[] = $taiKhoan;
      }
    }

    // Tính tổng số bản ghi
    $sqlTotalRecords = "SELECT COUNT(*) AS total FROM taikhoan";

    if ($keyword !== null) {
      $sqlTotalRecords .= " WHERE username LIKE '%$keyword%'";
    }

    $resultTotalRecords = $this->conn->query($sqlTotalRecords);
    $totalRecords = $resultTotalRecords->fetch_assoc()['total'];

    return [$dsTaiKhoan, $totalRecords];
  }

  public function themTaiKhoan($username, $password, $priv)
  {
    // Kiểm tra xem tài khoản đã tồn tại chưa
    $check_sql = "SELECT COUNT(*) AS total FROM taikhoan WHERE username = '$username'";
    $check_result = $this->conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    $total_records = $check_row['total'];

    // Nếu tài khoản đã tồn tại thì trả về false
    if ($total_records > 0) {
      return false;
    }

    $sql = "INSERT INTO taikhoan (username, password, priv) VALUES ('$username', '$password', '$priv')";

    // Nếu thêm thành công thì trả về true, ngược lại trả về false
    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  public function xoaTaiKhoan($username)
  {
    $sql = "DELETE FROM taikhoan WHERE username = '$username'";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  public function capNhatTaiKhoan($username, $password, $priv)
  {
    $sql = "UPDATE taikhoan SET password = '$password', priv = '$priv' WHERE username = '$username'";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }
}
