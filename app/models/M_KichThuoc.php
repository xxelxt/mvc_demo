<?php

require_once 'E_KichThuoc.php';

class KichThuocModel
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // Hàm lấy danh sách kích thước từ CSDL
  public function layDanhSach($recordPerPage, $offset, $keyword = null)
  {
    // Tạo mảng chứa danh sách kích thước
    $dsKichThuoc = [];

    $sql = "SELECT * FROM kichthuoc";

    // Nếu có từ khóa tìm kiếm thì thêm điều kiện WHERE vào string sql
    if ($keyword !== null) {
      $sql .= " WHERE tenkt LIKE '%$keyword%'"; // chèn thêm vào sau $sql
    }

    // Thêm string LIMIT vào câu truy vấn để phân trang
    $sql .= " LIMIT $offset, $recordPerPage";

    $result = $this->conn->query($sql);

    // Lặp qua kết quả truy vấn được từ fetch_assoc để lấy dữ liệu, tạo đối tượng KichThuoc và thêm vào mảng
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $kichThuoc = new KichThuoc($row['makt'], $row['tenkt']);
        $dsKichThuoc[] = $kichThuoc;
      }
    }

    // Tính tổng số bản ghi
    $sqlTotalRecords = "SELECT COUNT(*) AS total FROM kichthuoc";

    if ($keyword !== null) {
      $sqlTotalRecords .= " WHERE tenkt LIKE '%$keyword%'";
    }

    $resultTotalRecords = $this->conn->query($sqlTotalRecords);
    $totalRecords = $resultTotalRecords->fetch_assoc()['total'];

    return [$dsKichThuoc, $totalRecords];
  }

  // Lấy tên kích thước từ mã kích thước
  public function layTenKT($makt)
  {
    $sql = "SELECT tenkt FROM kichthuoc WHERE makt = '$makt'";
    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['tenkt'];
  }
  public function layDanhSachKT()
  {
    $dsKichThuoc = [];

    $sql = "SELECT * FROM kichthuoc";

    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $kichThuoc = new KichThuoc($row['makt'], $row['tenkt']);
        $dsKichThuoc[] = $kichThuoc;
      }
    }

    return $dsKichThuoc;
  }

  public function themKT($makt, $tenkt)
  {
    // Kiểm tra xem mã kích thước đã tồn tại chưa
    $check_sql = "SELECT COUNT(*) AS total FROM kichthuoc WHERE makt = '$makt'";
    $check_result = $this->conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    $total_records = $check_row['total'];

    // Nếu tồn tại thì dừng việc thêm
    if ($total_records > 0) {
      return false;
    }

    $sql = "INSERT INTO kichthuoc (makt, tenkt) VALUES ('$makt', '$tenkt')";

    // Thực thi câu truy vấn, nếu thành công thì trả về true, ngược lại trả về false
    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  public function xoaKT($makt)
  {
    // Kiểm tra xem có sản phẩm nào thuộc kích thước này không
    $sql_check_sp = "SELECT COUNT(*) as count FROM ktsanpham WHERE makt = '$makt'";
    $result_check_sp = $this->conn->query($sql_check_sp);
    $row_check_sp = $result_check_sp->fetch_assoc();

    if ($row_check_sp['count'] > 0) {
      return false;
    }

    $sql = "DELETE FROM kichthuoc WHERE makt = '$makt'";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  public function capNhatKT($makt, $tenkt)
  {
    $sql = "UPDATE kichthuoc SET tenkt = '$tenkt' WHERE makt = '$makt'";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }
}
