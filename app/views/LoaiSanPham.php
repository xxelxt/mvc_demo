<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include("../../config/database.php");
    require_once("../controllers/C_LoaiSanPham.php");

    $quyen = isset($_SESSION['quyen']) ? $_SESSION['quyen'] : '';

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loại sản phẩm</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="../../public/assets/css/style.css">
        <style>
            .fixed-height-table td {
                height: 55px;
            }
        </style>
    </head>

    <body>
        <div class="container mt-4">
            <?php include("header.php"); ?>
        </div>

        <div class="container mt-4">
            <div class="m-lg-1 d-flex justify-content-between align-items-center mb-3">
                <h2>Danh sách loại sản phẩm</h2>
                <div class="d-flex align-items-center">
                    <!-- <form id="searchForm" method="POST" action="LoaiSanPham.php" class="d-flex">
          <div class="input-group">
            <?php
                        // Kiểm tra xem đã có từ khóa tìm kiếm được gửi đi từ form chưa
                        $search = isset($_POST['search']) ? $_POST['search'] : '';
                    ?>
            <input id="searchInput" type="text" name="search" class="form-control me-2" placeholder="Nhập tên loại sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" name="submit_search" class="btn btn-primary">Tìm kiếm</button>
          </div>
        </form> -->

                    <?php
                        if ($quyen == 1) {
                            ?>
                            <button type="button" class="btn btn-primary ms-4" data-bs-toggle="modal"
                                    data-bs-target="#addModalLSP">
                                Thêm mới &nbsp; <i class="fas fa-plus"></i>
                            </button>
                            <?php
                        }
                    ?>
                </div>
            </div>

            <!-- Modal thêm mới loại sản phẩm -->
            <div class="modal fade" id="addModalLSP" tabindex="-1" aria-labelledby="addModalLSPLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLSPLabel">Thêm mới loại sản phẩm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../controllers/C_LoaiSanPham.php" method="POST">
                                <div class="mb-3">
                                    <label for="maloai" class="form-label">Mã loại:</label>
                                    <input type="text" class="form-control" id="maloai" name="maloai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tenloai" class="form-label">Tên loại:</label>
                                    <input type="text" class="form-control" id="tenloai" name="tenloai" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiển thị danh sách loại sản phẩm -->
        <?php
            global $conn;

            $recordPerPage = 6;

            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            $offset = ($page - 1) * $recordPerPage;

            $LoaiSanPhamModel = new LoaiSanPhamModel($conn);

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_search'])) {
                if (isset($_POST['search'])) {
                    $searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
                    list($listLoaiSanPham, $totalRecords) = $LoaiSanPhamModel -> layDanhSach($recordPerPage, $offset, $searchKeyword);
                }
            } else {
                list($listLoaiSanPham, $totalRecords) = $LoaiSanPhamModel -> layDanhSach($recordPerPage, $offset, null);
            }

            $numberOfPage = ceil($totalRecords / $recordPerPage);

            if (!empty($listLoaiSanPham)) {
                ?>
                <div class="container mt-4">
                    <!-- <h2>Danh sách loại sản phẩm</h2> -->
                    <div class="table-responsive">
                        <table class="table table-hover fixed-height-table">
                            <thead>
                                <tr>
                                    <th>Mã loại</th>
                                    <th>Tên loại</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    foreach ($listLoaiSanPham as $loaiSanPham) {
                                        ?>
                                        <tr>
                                            <td class="align-middle"><?php echo $loaiSanPham -> getMaloai(); ?></td>
                                            <td class="align-middle"><?php echo $loaiSanPham -> getTenloai(); ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <?php
                                                        if ($quyen == 1) {
                                                            ?>
                                                            <form id="deleteFormLSP_<?php echo $loaiSanPham -> getMaloai(); ?>"
                                                                  action="../controllers/C_LoaiSanPham.php"
                                                                  method="POST">
                                                                <input type="hidden" name="maloai"
                                                                       value="<?php echo $loaiSanPham -> getMaloai(); ?>">
                                                                <button type="button"
                                                                        class="btn btn-danger table-action-button"
                                                                        data-maloai="<?php echo $loaiSanPham -> getMaloai(); ?>"
                                                                        onclick="confirmDeleteLSP(this)">Xoá
                                                                </button>
                                                            </form>
                                                            <?php
                                                        }
                                                    ?>

                                                    <script>
                                                        function confirmDeleteLSP(button) {
                                                            var maloai = button.getAttribute('data-maloai');
                                                            var confirmMessage = 'Bạn có chắc chắn muốn xoá mã loại hàng ' + maloai + '?';
                                                            if (confirm(confirmMessage)) {
                                                                var formId = 'deleteFormLSP_' + maloai;
                                                                document.getElementById(formId).submit();
                                                            }
                                                        }
                                                    </script>

                                                    <?php
                                                        if ($quyen == 1 || $quyen == 2) {
                                                            ?>
                                                            <button type="button"
                                                                    class="btn btn-primary table-action-button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal<?php echo $loaiSanPham -> getMaloai(); ?>">
                                                                Sửa
                                                            </button>
                                                            <?php
                                                        }
                                                    ?>

                                                    <div class="modal fade"
                                                         id="editModal<?php echo $loaiSanPham -> getMaloai(); ?>"
                                                         tabindex="-1" aria-labelledby="editModalLabel"
                                                         aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel">Chỉnh
                                                                        sửa loại sản phẩm</h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="../controllers/C_LoaiSanPham.php"
                                                                          method="POST">
                                                                        <div class="mb-3">
                                                                            <label for="maloai_edit" class="form-label">Mã
                                                                                loại:</label>
                                                                            <input type="text" class="form-control"
                                                                                   id="maloai_edit" name="maloai_edit"
                                                                                   value="<?php echo $loaiSanPham -> getMaloai(); ?>"
                                                                                   readonly>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="tenloai_edit"
                                                                                   class="form-label">Tên loại:</label>
                                                                            <input type="text" class="form-control"
                                                                                   id="tenloai_edit" name="tenloai_edit"
                                                                                   value="<?php echo $loaiSanPham -> getTenloai(); ?>"
                                                                                   required>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            Lưu thay đổi
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination buttons -->
                    <!-- <div class="container mt-4">
                      <form method="post" action="LoaiSanPham.php"> -->
                    <div class="d-flex justify-content-center mt-4">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $numberOfPage; $i++) {
                                ?>
                                <li class="page-item <?php if ($i == $page)
                                    echo 'active'; ?>">
                                    <a class="page-link"
                                       href="LoaiSanPham.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    <!-- <button type="submit" class="page-link" name="page" value="<?php echo $i; ?>"><?php echo $i; ?></button> -->
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- </form>
                    </div> -->

                </div>
                <?php
            } else {
                ?>
                <div class="container">
                    <h6 style="font-weight: normal;">Không có kết quả tìm kiếm nào.</h6>
                </div>
                <?php
            }
        ?>
    </body>

</html>