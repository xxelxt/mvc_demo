<?php

    require_once 'E_MauSac.php';

    class MauSacModel {
        private $conn;

        public function __construct($conn) {
            $this -> conn = $conn;
        }

        // Hàm lấy danh sách màu sắc từ CSDL
        public function layDanhSach($recordPerPage, $offset, $keyword = null) {
            // Tạo mảng chứa danh sách màu sắc
            $dsMauSac = [];

            $sql = "SELECT * FROM mausac";

            // Nếu có từ khóa tìm kiếm thì thêm điều kiện WHERE vào string sql
            if ($keyword !== null) {
                $sql .= " WHERE tenmau LIKE '%$keyword%'";
            }

            // Thêm string LIMIT vào câu truy vấn để phân trang
            $sql .= " LIMIT $offset, $recordPerPage";

            $result = $this -> conn -> query($sql);

            // Lặp qua kết quả truy vấn được từ fetch_assoc để lấy dữ liệu, tạo đối tượng MauSac và thêm vào mảng
            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $mauSac = new MauSac($row['mamau'], $row['tenmau']);
                    $dsMauSac[] = $mauSac;
                }
            }

            // Tính tổng số bản ghi
            $sqlTotalRecords = "SELECT COUNT(*) AS total FROM mausac";

            if ($keyword !== null) {
                $sqlTotalRecords .= " WHERE tenmau LIKE '%$keyword%'";
            }

            $resultTotalRecords = $this -> conn -> query($sqlTotalRecords);
            $totalRecords = $resultTotalRecords -> fetch_assoc()['total'];

            return [$dsMauSac, $totalRecords];
        }

        // Lấy tên màu từ mã màu
        public function layTenMau($mamau) {
            $sql = "SELECT tenmau FROM mausac WHERE mamau = '$mamau'";
            $result = $this -> conn -> query($sql);
            $row = $result -> fetch_assoc();
            return $row['tenmau'];
        }

        public function layDanhSachMauSac() {
            $dsMauSac = [];

            $sql = "SELECT * FROM mausac";
            $result = $this -> conn -> query($sql);

            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $mauSac = new MauSac($row['mamau'], $row['tenmau']);
                    $dsMauSac[] = $mauSac;
                }
            }

            return $dsMauSac;
        }

        public function themMauSac($mamau, $tenmau) {
            // Kiểm tra xem mã màu đã tồn tại chưa
            $check_sql = "SELECT COUNT(*) AS total FROM mausac WHERE mamau = '$mamau'";
            $check_result = $this -> conn -> query($check_sql);
            $check_row = $check_result -> fetch_assoc();
            $total_records = $check_row['total'];

            // Nếu tồn tại thì dừng việc thêm
            if ($total_records > 0) {
                return false;
            }

            $sql = "INSERT INTO mausac (mamau, tenmau) VALUES ('$mamau', '$tenmau')";

            // Thực thi câu truy vấn, nếu thành công thì trả về true, ngược lại trả về false
            if ($this -> conn -> query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }

        public function xoaMauSac($mamau) {
            // Kiểm tra xem có sản phẩm nào trong CSDL có màu này không
            $sql_check_sp = "SELECT COUNT(*) as count FROM mausanpham WHERE mamau = '$mamau'";
            $result_check_sp = $this -> conn -> query($sql_check_sp);
            $row_check_sp = $result_check_sp -> fetch_assoc();

            if ($row_check_sp['count'] > 0) {
                return false;
            }

            $sql = "DELETE FROM mausac WHERE mamau = '$mamau'";

            if ($this -> conn -> query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }

        public function capNhatMauSac($mamau, $tenmau) {
            $sql = "UPDATE mausac SET tenmau = '$tenmau' WHERE mamau = '$mamau'";

            if ($this -> conn -> query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }
    }
