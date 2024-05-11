<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// include("../../config/database.php");
require_once("../controllers/C_TaiKhoan.php");

$quyen = isset($_SESSION['quyen']) ? $_SESSION['quyen'] : '';

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tài khoản</title>
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

  <?php if ($quyen == 1) : ?>

    <div class="container mt-4">
      <div class="m-lg-1 d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách tài khoản</h2>
        <div class="d-flex align-items-center">
          <!-- <form id="searchForm" method="POST" action="TaiKhoan.php" class="d-flex">
            <div class="input-group">
              <?php
              // Kiểm tra xem đã có từ khóa tìm kiếm được gửi đi từ form chưa
              $search = isset($_POST['search']) ? $_POST['search'] : '';
              ?>
              <input id="searchInput" type="text" name="search" class="form-control me-2" placeholder="Nhập tên tài khoản..." value="<?php echo htmlspecialchars($search); ?>">
              <button type="submit" name="submit_search" class="btn btn-primary">Tìm kiếm</button>
            </div>
          </form> -->

          <?php
          if ($quyen == 1) {
          ?>
            <button type="button" class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#addModalTaikhoan">
              Thêm mới &nbsp; <i class="fas fa-plus"></i>
            </button>
          <?php
          }
          ?>
        </div>
      </div>

      <!-- Modal thêm mới tài khoản -->
      <div class="modal fade" id="addModalTaikhoan" tabindex="-1" aria-labelledby="addModalTaikhoanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addModalTaikhoanLabel">Thêm mới tài khoản</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="../controllers/C_TaiKhoan.php" method="POST">
                <div class="mb-3">
                  <label for="username" class="form-label">Tên tài khoản:</label>
                  <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Mật khẩu:</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                  <label for="priv" class="form-label">Quyền:</label>
                  <input type="number" class="form-control" id="priv" name="priv" min="0" required>
                </div>
                <button type="submit" class="btn btn-primary">Thêm</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Hiển thị danh sách tài khoản -->
    <?php

    $recordPerPage = 6;

    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    $offset = ($page - 1) * $recordPerPage;

    $TaiKhoanModel = new TaiKhoanModel($conn);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_search'])) {
      if (isset($_POST['search'])) {
        $searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
        list($listTaiKhoan, $totalRecords) = $TaiKhoanModel->timKiemTheoTen($recordPerPage, $offset, $searchKeyword);
      }
    } else {
      list($listTaiKhoan, $totalRecords) = $TaiKhoanModel->layDanhSach($recordPerPage, $offset);
    }

    $numberOfPage = ceil($totalRecords / $recordPerPage);

    if (!empty($listTaiKhoan)) {
    ?>
      <div class="container mt-4">
        <!-- <h2>Danh sách tài khoản</h2> -->
        <div class="table-responsive">
          <table class="table table-hover fixed-height-table">
            <thead>
              <tr>
                <th>Tên tài khoản</th>
                <th>Mật khẩu</th>
                <th>Quyền</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              <?php
              foreach ($listTaiKhoan as $taiKhoan) {
              ?>
                <tr>
                  <td class="align-middle"><?php echo $taiKhoan->getUsername(); ?></td>
                  <td class="align-middle"><?php echo $taiKhoan->getPassword(); ?></td>
                  <td class="align-middle"><?php echo $taiKhoan->getPriv(); ?></td>
                  <td>
                    <div class="table-actions">
                      <?php
                      if ($quyen == 1) {
                      ?>
                        <form id="deleteFormTK_<?php echo $taiKhoan->getUsername(); ?>" action="../controllers/C_TaiKhoan.php" method="POST">
                          <input type="hidden" name="username" value="<?php echo $taiKhoan->getUsername(); ?>">
                          <button type="button" class="btn btn-danger table-action-button" data-user="<?php echo $taiKhoan->getUsername(); ?>" onclick="confirmDeleteTK(this)">Xoá</button>
                        </form>
                      <?php
                      }
                      ?>

                      <script>
                        function confirmDeleteTK(button) {
                          var user = button.getAttribute('data-user');
                          var confirmMessage = 'Bạn có chắc chắn muốn xoá tài khoản có username ' + user + '?';
                          if (confirm(confirmMessage)) {
                            var formId = 'deleteFormTK_' + user;
                            document.getElementById(formId).submit();
                          }
                        }
                      </script>

                      <?php
                      if ($quyen == 1 || $quyen == 2) {
                      ?>
                        <button type="button" class="btn btn-primary table-action-button" data-bs-toggle="modal" data-bs-target="#editModalTaikhoan<?php echo $taiKhoan->getUsername(); ?>">
                          Sửa
                        </button>
                      <?php
                      }
                      ?>

                      <div class="modal fade" id="editModalTaikhoan<?php echo $taiKhoan->getUsername(); ?>" tabindex="-1" aria-labelledby="editModalTaikhoanLabel<?php echo $taiKhoan->getUsername(); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="editModalTaikhoanLabel<?php echo $taiKhoan->getUsername(); ?>">Chỉnh sửa tài khoản</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../controllers/C_TaiKhoan.php" method="POST">
                                <div class="mb-3">
                                  <label for="username_edit" class="form-label">Tên tài khoản:</label>
                                  <input type="text" class="form-control" id="username_edit" name="username_edit" value="<?php echo $taiKhoan->getUsername(); ?>" readonly>
                                </div>
                                <div class="mb-3">
                                  <label for="password_edit" class="form-label">Mật khẩu:</label>
                                  <input type="password" class="form-control" id="password_edit" name="password_edit" value="<?php echo $taiKhoan->getPassword(); ?>" required>
                                </div>
                                <div class="mb-3">
                                  <label for="priv_edit" class="form-label">Quyền:</label>
                                  <input type="number" class="form-control" id="priv_edit" name="priv_edit" value="<?php echo $taiKhoan->getPriv(); ?>" min="0" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
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
        <form method="post" action="taikhoan.php"> -->
        <div class="d-flex justify-content-center mt-4">
          <ul class="pagination">
            <?php for ($i = 1; $i <= $numberOfPage; $i++) {
            ?>
              <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="TaiKhoan.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
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

  <?php else : ?>
    <div class="container mt-4">
      <h3>&nbsp;Bạn không có quyền truy cập trang này.</h3>
    </div>
  <?php endif; ?>
</body>

</html>