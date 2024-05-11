<?php

    require_once 'E_SanPham.php';

    class SanPhamModel {
        private $conn;
        private $cache;

        public function __construct($conn) {
            $this -> conn = $conn;
            $this -> cache = new redisCache();
        }

        // Hàm lấy danh sách sản phẩm và phân trang và lọc dựa theo các thuộc tính truyền vào
        /* public function layDanhSach($recordPerPage, $offset, $searchKeyword = null, $categoryId = null, $minPrice = null, $maxPrice = null, $colorFilters = [], $sizeFilters = [], $sort = 'default') {
            // Tạo mảng chứa danh sách sản phẩm
            $dsSanPham = [];

            $sql = "SELECT * FROM sanpham";

            // Tạo mảng chứa điều kiện WHERE dựa trên các thuộc tính truyền vào
            $whereConditions = [];

            // Nếu có từ khoá tìm kiếm thì thêm điều kiện WHERE vào string whereConditions
            if ($searchKeyword !== null) {
                $whereConditions[] = "tensp LIKE '%" . $this -> conn -> real_escape_string($searchKeyword) . "%'";
            }

            // Nếu có sắp xếp sản phẩm theo loại
            if ($categoryId !== null) {
                $whereConditions[] = "maloai = '" . $this -> conn -> real_escape_string($categoryId) . "'";
            }

            // Nếu có mức giá tối thiểu
            if ($minPrice !== null) {
                $whereConditions[] = "gia >= " . $this -> conn -> real_escape_string($minPrice);
            }

            // Nếu có mức giá tối đa
            if ($maxPrice !== null) {
                $whereConditions[] = "gia <= " . $this -> conn -> real_escape_string($maxPrice);
            }

            // Nếu có lọc theo 1 hoặc nhiều màu sắc
            if (!empty($colorFilters)) {
                $colorCondition = "masp IN (SELECT DISTINCT masp FROM mausanpham WHERE mamau IN ('" . implode("','", array_map([$this -> conn, 'real_escape_string'], $colorFilters)) . "'))";
                $whereConditions[] = $colorCondition;
            }

            // Nếu có lọc theo 1 hoặc nhiều kích thước
            if (!empty($sizeFilters)) {
                $sizeCondition = "masp IN (SELECT DISTINCT masp FROM ktsanpham WHERE makt IN ('" . implode("','", array_map([$this -> conn, 'real_escape_string'], $sizeFilters)) . "'))";
                $whereConditions[] = $sizeCondition;
            }

            // Thêm các điều kiện WHERE vào string sql, tách giữa các điều kiện bởi " AND "
            if (!empty($whereConditions)) {
                $sql .= " WHERE " . implode(" AND ", $whereConditions);
            }

            // Nếu có sắp xếp sản phẩm theo mức giá
            switch ($sort) {
                case 'price_desc':
                    $sql .= " ORDER BY gia DESC";
                    break;
                case 'price_asc':
                    $sql .= " ORDER BY gia ASC";
                    break;
                default:
                    // Mặc định không cần ORDER BY
                    break;
            }

            // Thêm LIMIT vào câu truy vấn để phân trang
            $sql .= " LIMIT " . $this -> conn -> real_escape_string($offset) . ", " . $this -> conn -> real_escape_string($recordPerPage);

            $result = $this -> conn -> query($sql);

            // Lặp qua kết quả truy vấn được từ fetch_assoc để lấy dữ liệu, tạo đối tượng SanPham và thêm vào mảng
            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $sanPham = new SanPham(
                        $row['masp'],
                        $row['tensp'],
                        $row['gia'],
                        $row['soluong'],
                        $row['hinhanh'],
                        $row['maloai']
                    );

                    $dsSanPham[] = $sanPham;
                }
            }

            // Tính tổng số bản ghi
            $sqlTotalRecords = "SELECT COUNT(*) AS total FROM sanpham";

            if (!empty($whereConditions)) {
                $sqlTotalRecords .= " WHERE " . implode(" AND ", $whereConditions);
            }

            $resultTotalRecords = $this -> conn -> query($sqlTotalRecords);
            $totalRecords = $resultTotalRecords -> fetch_assoc()['total'];

            return [$dsSanPham, $totalRecords];
        } */

        public function layDanhSach($recordPerPage, $offset) {
            // Tạo mảng chứa danh sách sản phẩm
            $dsSanPham = [];

            // Query SQL để lấy sản phẩm dựa trên phân trang
            $sql = "SELECT * FROM sanpham LIMIT " . $this->conn->real_escape_string($offset) . ", " . $this->conn->real_escape_string($recordPerPage);

            // Thực hiện query
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $masp = $row['masp'];
                    $cacheKey = 'sanpham_' . $masp;

                    // Kiểm tra xem sản phẩm đã có trong cache chưa
                    $cachedProduct = $this -> cache -> get($cacheKey);
                    if ($cachedProduct) {
                        // Nếu thông tin sản phẩm đã tồn tại trong cache, decode JSON và tạo đối tượng SanPham từ cache
                        $productData = json_decode($cachedProduct, true);
                        $sanPham = new SanPham(
                            $productData['masp'],
                            $productData['tensp'],
                            $productData['gia'],
                            $productData['soluong'],
                            $productData['hinhanh'],
                            $productData['maloai']
                        );
                    } else {
                        // Nếu chưa tồn tại trong cache, query từ database và lưu vào cache
                        $sanPham = new SanPham(
                            $row['masp'],
                            $row['tensp'],
                            $row['gia'],
                            $row['soluong'],
                            $row['hinhanh'],
                            $row['maloai']
                        );
                        $this -> cache -> set($cacheKey, json_encode($row));
                    }

                    // Lưu các đối tượng SanPham vào mảng 'dsSanPham'
                    $dsSanPham[] = $sanPham;
                }
            }

            // Lấy tổng số bản ghi từ cache nếu có
            $totalRecords = $this -> cache -> get('totalRecords');

            // Nếu chưa, query tổng số bản ghi từ database và lưu vào cache
            if (!$totalRecords) {
                $sqlTotalRecords = "SELECT COUNT(*) AS total FROM sanpham";
                $resultTotalRecords = $this -> conn -> query($sqlTotalRecords);
                $totalRecords = $resultTotalRecords -> fetch_assoc()['total'];
                $this -> cache -> set('totalRecords', $totalRecords);
            }

            return [$dsSanPham, $totalRecords];
        }

        public function layDanhSachKichThuoc() {
            $sql = "SELECT * FROM kichthuoc";
            $result = $this -> conn -> query($sql);
            $danhSachKichThuoc = [];

            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $danhSachKichThuoc[] = $row;
                }
            }

            return $danhSachKichThuoc;
        }

        public function layDanhSachMauSac() {
            $sql = "SELECT * FROM mausac";
            $result = $this -> conn -> query($sql);
            $danhSachMauSac = [];

            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $danhSachMauSac[] = $row;
                }
            }

            return $danhSachMauSac;
        }

        public function themSanPham($sanPham, $mausac, $kichthuoc) {
            // Lấy thông tin sản phẩm từ đối tượng SanPham truyền vào
            $masp = $sanPham -> getMaSP();
            $tensp = $sanPham -> getTenSP();
            $gia = $sanPham -> getGia();
            $soluong = $sanPham -> getSoLuong();
            $hinhanh = $sanPham -> getHinhAnh();
            $maloai = $sanPham -> getMaLoai();

            // Tạo câu truy vấn SQL để thêm sản phẩm vào CSDL (trừ màu sắc và kích thước)
            $sql = "INSERT INTO sanpham (masp, tensp, gia, soluong, hinhanh, maloai) 
            VALUES ('$masp', '$tensp', '$gia', '$soluong', '$hinhanh', '$maloai')";

            // Nếu thêm sản phẩm thành công thì thêm màu sắc và kích thước vào bảng mausanpham và ktsanpham
            if ($this -> conn -> query($sql) === TRUE) {
                $this -> insertNewAssociations($masp, $mausac, 'mausac', 'mamau', 'tenmau', 'mausanpham');
                $this -> insertNewAssociations($masp, $kichthuoc, 'kichthuoc', 'makt', 'tenkt', 'ktsanpham');

                return true;
            } else {
                return false;
            }
        }

        /*
         * Hàm thêm các màu sắc hoặc kích thước mới vào bảng mausanpham hoặc ktsanpham
         *
         * $masp: mã sản phẩm
         * $items: mảng chứa các màu sắc hoặc kích thước cần thêm
         *
         * $itemName: tên bảng chứa các màu sắc hoặc kích thước
         * $itemIdField: tên trường chứa mã màu sắc hoặc kích thước
         * $itemNameField: tên trường chứa tên màu sắc hoặc kích thước
         * $assocTable: tên bảng chứa thông tin giữa sản phẩm và màu sắc hoặc kích thước
         *
         */

        private function insertNewAssociations($masp, $items, $itemName, $itemIdField, $itemNameField, $assocTable) {
            // Tạo mảng chứa mã màu sắc hoặc kích thước
            $itemIds = [];

            // Lặp qua mảng chứa màu sắc hoặc kích thước cần thêm
            foreach ($items as $item) {
                // Tạo câu truy vấn SQL để lấy mã màu sắc hoặc kích thước từ tên màu sắc hoặc kích thước
                $sql = "SELECT $itemIdField FROM $itemName WHERE $itemNameField = '$item'";
                $result = $this -> conn -> query($sql);
                if ($result -> num_rows > 0) {
                    $row = $result -> fetch_assoc();
                    // Thêm mã màu sắc hoặc kích thước vào mảng $itemIds
                    $itemIds[] = $row[$itemIdField];
                }
            }

            // Lặp qua mảng chứa mã màu sắc hoặc kích thước để thêm vào bảng mausanpham hoặc ktsanpham
            foreach ($itemIds as $itemId) {
                $insertSql = "INSERT INTO $assocTable (masp, $itemIdField) VALUES ('$masp', '$itemId')";
                $this -> conn -> query($insertSql);
            }
        }

        // Sử dụng để cập nhật màu sắc hoặc kích thước cho sản phẩm
        // (xoá các màu sắc hoặc kích thước cũ và thêm mới)
        private function deleteExistingAssociations($masp, $table) {
            $deleteSql = "DELETE FROM $table WHERE masp = '$masp'";
            $this -> conn -> query($deleteSql);
        }

        public function capNhatSanPham($sanPham, $mausac, $kichthuoc) {
            // Lấy thông tin sản phẩm từ đối tượng SanPham truyền vào
            $masp = $sanPham -> getMaSP();
            $tensp = $sanPham -> getTenSP();
            $gia = $sanPham -> getGia();
            $soluong = $sanPham -> getSoLuong();
            $maloai = $sanPham -> getMaLoai();
            $hinhanh = $sanPham -> getHinhAnh();

            // Tạo mảng chứa các cột cần cập nhật
            $updateColumns = [
                "tensp = '$tensp'",
                "gia = '$gia'",
                "soluong = '$soluong'",
                "maloai = '$maloai'"
            ];

            // Nếu có hình ảnh mới thì thêm cột hình ảnh vào mảng cột cần cập nhật
            if ($hinhanh !== null) {
                $updateColumns[] = "hinhanh = '$hinhanh'";
            }

            // Chuyển mảng cột cần cập nhật thành chuỗi
            $updateColumnsString = implode(', ', $updateColumns);

            $sql = "UPDATE sanpham 
            SET $updateColumnsString
            WHERE masp = '$masp'";

            // Nếu cập nhật sản phẩm thành công thì cập nhật màu sắc và kích thước
            if ($this -> conn -> query($sql) === TRUE) {
                // Xoá các màu sắc và kích thước cũ
                $this -> deleteExistingAssociations($masp, 'mausanpham');
                $this -> deleteExistingAssociations($masp, 'ktsanpham');

                // Thêm các màu sắc và kích thước mới
                $this -> insertNewAssociations($masp, $mausac, 'mausac', 'mamau', 'tenmau', 'mausanpham');
                $this -> insertNewAssociations($masp, $kichthuoc, 'kichthuoc', 'makt', 'tenkt', 'ktsanpham');

                return true;
            } else {
                return false;
            }
        }

        public function xoaSanPham($masp) {
            // Xoá thông tin về sản phẩm và kích thước của sản phẩm trong bảng ktsanpham
            $sql_delete_links = "DELETE FROM ktsanpham WHERE masp = '$masp'";

            if ($this -> conn -> query($sql_delete_links) !== TRUE) {
                return false;
            }

            // Xoá thông tin về sản phẩm và màu sắc của sản phẩm trong bảng mausanpham
            $sql_delete_links = "DELETE FROM ktsanpham WHERE masp = '$masp'";

            if ($this -> conn -> query($sql_delete_links) !== TRUE) {
                return false;
            }

            $sql = "DELETE FROM sanpham WHERE masp = '$masp'";

            if ($this -> conn -> query($sql) === TRUE) {
                return true;
            } else {
                return false;
            }
        }
    }
