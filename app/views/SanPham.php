<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// include("../../config/database.php");
require_once("../controllers/C_SanPham.php");

$quyen = isset($_SESSION['quyen']) ? $_SESSION['quyen'] : '';

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sản phẩm</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="../../public/assets/css/style.css">
  <style>
    .card {
      height: 100%;
    }

    .image-container {
      width: 100%;
      height: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .image-container img {
      width: auto;
      height: auto;
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      background-color: #ffffff;
    }

    .checkbox-container {
      display: block;
      position: relative;
      padding-left: 35px;
      margin-bottom: 8px;
      cursor: pointer;
      font-size: 16px;
    }

    .colorCheckbox {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      background-color: #ccc;
    }

    .colorCheckbox:checked+.checkmark:after {
      content: "";
      position: absolute;
      display: block;
      left: 9px;
      top: 5px;
      width: 7px;
      height: 13px;
      border: solid white;
      border-width: 0 3px 3px 0;
      transform: rotate(45deg);
    }

    .colorCheckbox:checked+.checkmark {
      background-color: #007bff;
    }

    .sizeCheckbox {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    .checkbox-container {
      display: block;
      position: relative;
      padding-left: 35px;
      margin-bottom: 8px;
      cursor: pointer;
      font-size: 16px;
    }

    .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      height: 25px;
      width: 25px;
      background-color: #fff;
      border: 1px solid gray;
      border-radius: 3px;
    }

    .sizeCheckbox:checked+.checkmark:after {
      content: "";
      position: absolute;
      left: 9px;
      top: 5px;
      width: 7px;
      height: 13px;
      border: solid white;
      border-width: 0 3px 3px 0;
      transform: rotate(45deg);
    }

    .sizeCheckbox:checked+.checkmark {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-light {
      color: #fff;
      border-color: #dee2e6;
      background-color: transparent;
    }

    .btn-light:active {
      color: #fff;
      background-color: transparent;
      border-color: #dee2e6;
    }

    /* Hình tròn có viền cho size */
    .product-details {
      display: flex;
      justify-content: flex-start;
      align-items: center;
    }

    .size-circle,
    .color-circle {
      display: flex;
      justify-content: center;
      align-items: center;

      transition: background-color 0.5s ease, color 0.5s ease;

      width: 30px;
      height: 30px;
      border: 1px solid #cccccc;
      border-radius: 50%;
      margin-right: 5px;
      font-size: 13px;
    }

    .size-circle:hover,
    .color-circle:hover {
      background-color: #06121a;
      color: white;
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <?php include("header.php"); ?>
  </div>

  <div class="container mt-4">
    <div class="row">
      <div class="col-md-12">

        <!-- Modal thêm mới đơn đặt hàng -->
        <div class="modal fade" id="addModalSanpham" tabindex="-1" aria-labelledby="addModalSanphamLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addModalSanphamLabel">Thêm mới sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form action="../controllers/C_SanPham.php" method="POST" enctype="multipart/form-data">
                  <div class="row">
                    <!-- Cột 1: Các trường nhập -->
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label for="masp" class="form-label">Mã sản phẩm:</label>
                        <input type="text" class="form-control" id="masp" name="masp" required>
                      </div>
                      <div class="mb-3">
                        <label for="tensp" class="form-label">Tên sản phẩm:</label>
                        <input type="text" class="form-control" id="tensp" name="tensp" required>
                      </div>
                      <div class="mb-3">
                        <label for="gia" class="form-label">Giá:</label>
                        <input type="number" class="form-control" id="gia" name="gia" min="0" required>
                      </div>

                      <div class="mb-3">
                        <label for="soluong" class="form-label">Số lượng:</label>
                        <input type="number" class="form-control" id="soluong" name="soluong" min="0" required>
                      </div>
                      <div class="mb-3">
                        <label for="maloai" class="form-label">Tên loại:</label>
                        <select class="form-select" id="maloai" name="maloai" required>
                          <?php
                          $sql_categories = "SELECT maloai, tenloai FROM loaisanpham";
                          $result_categories = $conn->query($sql_categories);

                          if ($result_categories->num_rows > 0) {
                            while ($row_category = $result_categories->fetch_assoc()) {
                          ?>
                              <option value="<?php echo $row_category["maloai"]; ?>"><?php echo $row_category["tenloai"]; ?></option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>

                    <!-- Cột 2: Màu sắc & Kích thước -->
                    <div class="col-md-3">
                      <div class="mb-3">
                        <label for="mausac" class="form-label">Màu sắc:</label>
                        <?php
                        $sql_colors = "SELECT * FROM mausac";
                        $result_colors = $conn->query($sql_colors);

                        if ($result_colors->num_rows > 0) {
                          while ($row_color = $result_colors->fetch_assoc()) {
                        ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="mausac[]" id="mausac_<?php echo $row_color["mamau"]; ?>" value="<?php echo $row_color["tenmau"]; ?>">
                              <label class="form-check-label" for="mausac_<?php echo $row_color["mamau"]; ?>">
                                <?php echo $row_color["tenmau"]; ?>
                              </label>
                            </div>
                        <?php
                          }
                        }
                        ?>
                      </div>

                      <div class="mb-3">
                        <label for="kichthuoc" class="form-label">Kích thước:</label>
                        <?php
                        $sql_sizes = "SELECT * FROM kichthuoc";
                        $result_sizes = $conn->query($sql_sizes);

                        if ($result_sizes->num_rows > 0) {
                          while ($row_size = $result_sizes->fetch_assoc()) {
                        ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="kichthuoc[]" id="kichthuoc_<?php echo $row_size["makt"]; ?>" value="<?php echo $row_size["tenkt"]; ?>">
                              <label class="form-check-label" for="kichthuoc_<?php echo $row_size["makt"]; ?>">
                                <?php echo $row_size["tenkt"]; ?>
                              </label>
                            </div>
                        <?php
                          }
                        }
                        ?>
                      </div>
                    </div>

                    <!-- Cột 3: Hình ảnh hiện tại -->
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label for="hinhanh" class="form-label">Hình ảnh:</label>
                        <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" required onchange="previewImage()">
                      </div>
                      <div id="imagePreview" class="mt-3" style="display: none;">
                        <p>Xem trước hình ảnh:</p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <script>
                function previewImage() {
                  const fileInput = document.getElementById('hinhanh');
                  const imagePreview = document.getElementById('imagePreview');

                  // Kiểm tra xem người dùng đã chọn tập tin ảnh hay chưa
                  if (fileInput.files && fileInput.files[0]) {
                    // Hiển thị phần xem trước hình ảnh
                    imagePreview.style.display = 'block';

                    // Xóa nội dung xem trước cũ
                    imagePreview.innerHTML = '';

                    // Thêm dòng văn bản "Xem trước hình ảnh" vào phần tử div
                    const previewText = document.createElement('p');
                    previewText.textContent = 'Xem trước hình ảnh:';
                    imagePreview.appendChild(previewText);

                    const reader = new FileReader();

                    // Đọc dữ liệu hình ảnh dưới dạng URL
                    reader.onload = function(e) {
                      // Tạo một phần tử hình ảnh để hiển thị xem trước
                      const imgElement = document.createElement('img');
                      imgElement.setAttribute('src', e.target.result);
                      imgElement.setAttribute('alt', 'preview_image');
                      imgElement.style.maxWidth = '100%';
                      imgElement.style.height = 'auto';

                      // Thêm hình ảnh xem trước vào phần tử div
                      imagePreview.appendChild(imgElement);
                    }

                    // Đọc dữ liệu của tập tin hình ảnh
                    reader.readAsDataURL(fileInput.files[0]);
                  } else {
                    // Ẩn phần xem trước hình ảnh nếu người dùng không chọn tập tin ảnh
                    imagePreview.style.display = 'none';
                  }
                }
              </script>

            </div>
          </div>
        </div>

        <?php

        $recordPerPage = 8;

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $category = isset($_GET['loai']) ? $_GET['loai'] : null;
        $searchKeyword = isset($_POST['search']) ? $_POST['search'] : null;
        $minPrice = isset($_GET['minPrice']) ? $_GET['minPrice'] : null;
        $maxPrice = isset($_GET['maxPrice']) ? $_GET['maxPrice'] : null;
        $colorFilters = isset($_GET['colorFilter']) ? explode(',', $_GET['colorFilter']) : [];
        $sizeFilters = isset($_GET['sizeFilter']) ? explode(',', $_GET['sizeFilter']) : [];
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

        $offset = ($page - 1) * $recordPerPage;

        $SanPhamModel = new SanPhamModel($conn);

        list($listSanPham, $totalRecords) = $SanPhamModel->layDanhSach($recordPerPage, $offset, null, $category, $minPrice, $maxPrice, $colorFilters, $sizeFilters, $sort);

        $numberOfPage = ceil($totalRecords / $recordPerPage);

        if (isset($_GET['loai'])) {
          $maloai = $_GET['loai'];
          $sql_tenloai = "SELECT tenloai FROM loaisanpham WHERE maloai = '$maloai'";
          $result_tenloai = $conn->query($sql_tenloai);

          if ($result_tenloai->num_rows > 0) {
            $row_tenloai = $result_tenloai->fetch_assoc();
            $ten_loai_sanpham = $row_tenloai['tenloai'];
          } else {
            $ten_loai_sanpham = "Unknown";
          }
        } else {
          $ten_loai_sanpham = "Tất cả sản phẩm";
        }
        ?>

        <div class="container">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="col-md-3">
              <h2 class="mb-0 m-lg-1"><?php echo $ten_loai_sanpham; ?></h2>
            </div>

            <div class="col-md-9 d-flex justify-content-end">
              <!-- <form id="searchForm" method="POST" action="SanPham.php" class="d-flex ms-4">
                <div class="input-group">
                  <?php
                  // Kiểm tra xem đã có từ khóa tìm kiếm được gửi đi từ form chưa
                  $search = isset($_POST['search']) ? $_POST['search'] : '';
                  ?>
                  <input id="searchInput" type="text" name="search" class="form-control me-2" placeholder="Nhập tên sản phẩm..." value="<?php echo htmlspecialchars($search); ?>">
                  <button type="submit" name="submit_search" class="btn btn-primary">Tìm kiếm</button>
                </div>
              </form> -->

              <?php
              if ($quyen == 1) {
              ?>
                <button type="button" class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#addModalSanpham">
                  Thêm mới &nbsp; <i class="fas fa-plus"></i>
                </button>
              <?php
              }
              ?>
            </div>
          </div>

          <!-- Các filter để lọc ở đây -->
          <form id="filtersForm">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="col-md-12">
                <div class="row">
                  <!-- Lọc theo danh mục sản phẩm -->
                  <div class="col-md-5 d-flex justify-content-between">
                    <div class="dropdown">
                      <label for="maloai_filter" class="visually-hidden">Danh mục sản phẩm:</label>
                      <select id="maloai_filter" name="maloai_filter" class="form-select" aria-labelledby="dropdownMenuCategories">
                        <option value="" disabled selected>Danh mục sản phẩm</option>
                        <option value="">Tất cả sản phẩm</option>
                        <?php
                        $sql_categories = "SELECT maloai, tenloai FROM loaisanpham";
                        $result_categories = $conn->query($sql_categories);

                        if ($result_categories->num_rows > 0) {
                          while ($row_category = $result_categories->fetch_assoc()) {
                            $maloai = $row_category["maloai"];
                            $tenloai = $row_category["tenloai"];
                            echo "<option value='$maloai'>$tenloai</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>

                    <!-- Lọc theo màu sắc (có thể tick chọn nhiều màu) -->
                    <div class="dropdown">
                      <button style="color: black;" class="btn btn-light dropdown-toggle" type="button" id="colorDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Chọn màu sắc
                      </button>
                      <div class="dropdown-menu" aria-labelledby="colorDropdown" id="colorDropdownMenu">
                        <div id="colorOptions">
                          <?php
                          $sql_colors = "SELECT mamau, tenmau FROM mausac";
                          $result_colors = $conn->query($sql_colors);

                          if ($result_colors->num_rows > 0) {
                            while ($row_color = $result_colors->fetch_assoc()) {
                              $color_id = $row_color['mamau'];
                              $color_name = $row_color['tenmau'];
                              echo '<label class="checkbox-container">' .
                                '<input type="checkbox" class="colorCheckbox" value="' . $color_id . '">' .
                                '<span class="checkmark"></span>' .
                                $color_name .
                                '</label>';
                            }
                          }
                          ?>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="updateUrl()">Lọc</button>
                      </div>
                    </div>

                    <!-- Lọc theo kích thước (có thể tick chọn nhiều kích thước) -->
                    <div class="dropdown">
                      <button style="color: black;" class="btn btn-light dropdown-toggle" type="button" id="sizeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Chọn kích thước
                      </button>
                      <div class="dropdown-menu" aria-labelledby="sizeDropdown" id="sizeDropdownMenu">
                        <div id="sizeOptions">
                          <?php
                          $sql_sizes = "SELECT makt, tenkt FROM kichthuoc";
                          $result_sizes = $conn->query($sql_sizes);

                          if ($result_sizes->num_rows > 0) {
                            while ($row_size = $result_sizes->fetch_assoc()) {
                              $size_id = $row_size['makt'];
                              $size_name = $row_size['tenkt'];
                              echo '<label class="checkbox-container">' .
                                '<input type="checkbox" class="sizeCheckbox" value="' . $size_id . '">' .
                                '<span class="checkmark"></span>' .
                                $size_name .
                                '</label>';
                            }
                          }
                          ?>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="updateUrl()">Lọc</button>
                      </div>
                    </div>
                  </div>

                  <!-- Lọc theo mức giá (min, max) -->
                  <div class="col-md-4">
                    <div class="row">
                      <!-- Giá từ -->
                      <div class="col-md-6">
                        <input type="number" class="form-control" id="minPrice" name="minPrice" placeholder="Giá từ">
                      </div>
                      <!-- Giá đến -->
                      <div class="col-md-6">
                        <input type="number" class="form-control" id="maxPrice" name="maxPrice" placeholder="Giá đến">
                      </div>
                    </div>
                  </div>

                  <!-- Sắp xếp thứ tự sản phẩm theo giá -->
                  <div class="col-md-3">
                    <div class="d-flex">
                      <!-- Combobox -->
                      <form id="sortForm" action="SanPham.php" method="GET">
                        <select class="form-select" id="sort" name="sort">
                          <option value="default" <?php if (!isset($_GET['sort']) || $_GET['sort'] == 'default') echo 'selected'; ?>>Mặc định</option>
                          <option value="price_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') echo 'selected'; ?>>Giá cao đến thấp</option>
                          <option value="price_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') echo 'selected'; ?>>Giá thấp đến cao</option>
                        </select>
                      </form>
                    </div>
                  </div>

                  <script>
                    // Xử lý sự kiện khi chọn checkbox trong dropdown màu sắc hoặc kích thước
                    document.addEventListener('DOMContentLoaded', function() {

                      // Lấy các checkbox trong dropdown màu sắc
                      const colorCheckboxes = document.querySelectorAll('#colorDropdown input[type="checkbox"]');
                      colorCheckboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                          // Cập nhật dropdown text khi checkbox được chọn
                          let selectedColors = [];
                          colorCheckboxes.forEach(function(cb) {
                            if (cb.checked) {
                              selectedColors.push(cb.parentNode.textContent.trim());
                            }
                          });
                          document.querySelector('#colorDropdown button').textContent = selectedColors.length > 0 ? selectedColors.join(', ') : 'Chọn màu sắc';
                        });
                      });

                      // Lấy các checkbox trong dropdown kích thước
                      const sizeCheckboxes = document.querySelectorAll('#sizeDropdown input[type="checkbox"]');
                      sizeCheckboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                          // Cập nhật dropdown text khi checkbox được chọn
                          let selectedSizes = [];
                          sizeCheckboxes.forEach(function(cb) {
                            if (cb.checked) {
                              selectedSizes.push(cb.parentNode.textContent.trim());
                            }
                          });
                          document.querySelector('#sizeDropdown button').textContent = selectedSizes.length > 0 ? selectedSizes.join(', ') : 'Chọn kích thước';
                        });
                      });
                    });
                  </script>

                </div>
              </div>
            </div>
          </form>

        </div>
        <?php
        ob_start();
        if (!empty($listSanPham)) {
        ?>
          <div class="container">
            <!-- <h2>Tất cả sản phẩm</h2> -->
            <div class="row">
              <?php
              foreach ($listSanPham as $sanPham) {
              ?>
                <div class="col-md-3 mb-4 mt-2">
                  <div class="card">

                    <form action="SanPhamCT.php" method="GET">
                      <input type="hidden" name="masp" value="<?php echo $sanPham->getMaSP(); ?>">
                      <div class="image-container">
                        <?php
                        $hinhanh_path = "../../public/assets/images/" . $sanPham->getHinhAnh();
                        ?>
                        <button type="submit" class="btn btn-link p-0 m-0"><img src="<?php echo $hinhanh_path; ?>" class="card-img-top" alt="image"></button>
                      </div>
                    </form>

                    <div class="card-body">
                      <h4 class="card-title">
                        <a href="SanPhamCT.php?masp=<?php echo $sanPham->getMaSP(); ?>" style="text-decoration: none; color: inherit;">
                          <?php echo $sanPham->getTenSP(); ?>
                        </a>
                      </h4>
                      <p class="card-text" style="line-height: 1.4;">
                        Mã SP: <?php echo $sanPham->getMaSP(); ?><br>
                      <h5><?php echo number_format($sanPham->getGia()); ?>đ</h5>
                      </p>

                      <div class="mt-3">
                        <?php
                        // Hiển thị các size của sản phẩm
                        $sql_sizes = "SELECT kichthuoc.makt FROM kichthuoc INNER JOIN ktsanpham ON kichthuoc.makt = ktsanpham.makt WHERE masp = '" . $sanPham->getMaSP() . "'";
                        $result_sizes = $conn->query($sql_sizes);

                        if ($result_sizes->num_rows > 0) {
                          echo '<div class="product-details">';
                          while ($row_size = $result_sizes->fetch_assoc()) {
                            echo '<div class="size-circle">' . $row_size['makt'] . '</div>';
                          }
                          echo '</div>';
                        }
                        ?>
                      </div>

                    </div>

                    <?php
                    if ($quyen == 1 || $quyen == 2) {
                    ?>
                      <div class="card-footer">
                        <div class="table-actions" style="justify-content: flex-start;">

                          <?php
                          if ($quyen == 1) {
                          ?>
                            <form id="deleteFormSP_<?php echo $sanPham->getMaSP(); ?>" action="../controllers/C_SanPham.php" method="POST">
                              <input type="hidden" name="masp" value="<?php echo $sanPham->getMaSP(); ?>">
                              <button type="button" class="btn btn-danger table-action-button" onclick="confirmDeleteSP('<?php echo $sanPham->getMaSP(); ?>')">Xoá</button>
                            </form>
                          <?php
                          }
                          ?>

                          <script>
                            function confirmDeleteSP(masp) {
                              var confirmMessage = 'Bạn có chắc chắn muốn xoá mã hàng ' + masp + '?';
                              if (confirm(confirmMessage)) {
                                var formId = 'deleteFormSP_' + masp;
                                document.getElementById(formId).submit();
                              }
                            }
                          </script>

                          <?php
                          if ($quyen == 1 || $quyen == 2) {
                          ?>
                            <button type="button" class="btn btn-primary table-action-button" data-bs-toggle="modal" data-bs-target="#editModalSanpham<?php echo $sanPham->getMaSP(); ?>">
                              Sửa
                            </button>
                          <?php
                          }
                          ?>

                          <div class="modal fade" id="editModalSanpham<?php echo $sanPham->getMaSP(); ?>" tabindex="-1" aria-labelledby="editModalSanphamLabel<?php echo $sanPham->getMaSP(); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="editModalSanphamLabel<?php echo $sanPham->getMaSP(); ?>">Chỉnh sửa sản phẩm</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                  <form action="../controllers/C_SanPham.php" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                      <!-- Cột 1: Các trường nhập -->
                                      <div class="col-md-4">
                                        <div class="mb-3">
                                          <label for="masp_edit" class="form-label">Mã sản phẩm:</label>
                                          <input type="text" class="form-control" id="masp_edit" name="masp_edit" value="<?php echo $sanPham->getMaSP(); ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                          <label for="tensp_edit" class="form-label">Tên sản phẩm:</label>
                                          <input type="text" class="form-control" id="tensp_edit" name="tensp_edit" value="<?php echo $sanPham->getTenSP(); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                          <label for="gia_edit" class="form-label">Giá:</label>
                                          <input type="number" class="form-control" id="gia_edit" name="gia_edit" value="<?php echo $sanPham->getGia(); ?>" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                          <label for="soluong_edit" class="form-label">Số lượng:</label>
                                          <input type="number" class="form-control" id="soluong_edit" name="soluong_edit" value="<?php echo $sanPham->getSoLuong(); ?>" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                          <label for="maloai_edit" class="form-label">Tên loại:</label>
                                          <select class="form-select" id="maloai_edit" name="maloai_edit" required>
                                            <?php
                                            $sql_categories = "SELECT * FROM loaisanpham";
                                            $result_categories = $conn->query($sql_categories);

                                            if ($result_categories->num_rows > 0) {
                                              while ($row_category = $result_categories->fetch_assoc()) {
                                            ?>
                                                <option value="<?php echo $row_category["maloai"]; ?>" <?php if ($row_category["maloai"] == $sanPham->getMaLoai()) echo 'selected'; ?>><?php echo $row_category["tenloai"]; ?></option>
                                            <?php
                                              }
                                            }
                                            ?>
                                          </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                      </div>

                                      <!-- Cột 2: Màu sắc & Kích thước -->
                                      <div class="col-md-3">
                                        <div class="mb-3">
                                          <label for="mausac_edit" class="form-label">Màu sắc:</label>
                                          <?php
                                          // Truy vấn danh sách màu sắc đã được chọn cho sản phẩm
                                          $sql_selected_colors = "SELECT mamau FROM mausanpham WHERE masp = '" . $sanPham->getMaSP() . "'";
                                          $result_selected_colors = $conn->query($sql_selected_colors);

                                          // Tạo một mảng để lưu các mã màu sắc đã chọn
                                          $selected_colors = [];
                                          while ($row_selected_color = $result_selected_colors->fetch_assoc()) {
                                            $selected_colors[] = $row_selected_color['mamau'];
                                          }

                                          // Truy vấn danh sách tất cả màu sắc
                                          $sql_colors = "SELECT mamau, tenmau FROM mausac";
                                          $result_colors = $conn->query($sql_colors);

                                          if ($result_colors->num_rows > 0) {
                                            while ($row_color = $result_colors->fetch_assoc()) {
                                              $isChecked = in_array($row_color['mamau'], $selected_colors) ? 'checked' : '';
                                          ?>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="mausac_edit[]" id="mausac_edit_<?php echo $row_color["mamau"]; ?>" value="<?php echo $row_color["tenmau"]; ?>" <?php echo $isChecked; ?>>
                                                <label class="form-check-label" for="mausac_edit_<?php echo $row_color["mamau"]; ?>">
                                                  <?php echo $row_color["tenmau"]; ?>
                                                </label>
                                              </div>
                                          <?php
                                            }
                                          }
                                          ?>
                                        </div>

                                        <div class="mb-3">
                                          <label for="kichthuoc_edit" class="form-label">Kích thước:</label>
                                          <?php
                                          // Truy vấn danh sách kích thước đã được chọn cho sản phẩm
                                          $sql_selected_sizes = "SELECT makt FROM ktsanpham WHERE masp = '" . $sanPham->getMaSP() . "'";
                                          $result_selected_sizes = $conn->query($sql_selected_sizes);

                                          // Tạo một mảng để lưu các mã kích thước đã chọn
                                          $selected_sizes = [];
                                          while ($row_selected_size = $result_selected_sizes->fetch_assoc()) {
                                            $selected_sizes[] = $row_selected_size['makt'];
                                          }

                                          // Truy vấn danh sách tất cả kích thước
                                          $sql_sizes = "SELECT makt, tenkt FROM kichthuoc";
                                          $result_sizes = $conn->query($sql_sizes);

                                          if ($result_sizes->num_rows > 0) {
                                            while ($row_size = $result_sizes->fetch_assoc()) {
                                              $isChecked = in_array($row_size['makt'], $selected_sizes) ? 'checked' : '';
                                          ?>
                                              <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="kichthuoc_edit[]" id="kichthuoc_edit_<?php echo $row_size["makt"]; ?>" value="<?php echo $row_size["tenkt"]; ?>" <?php echo $isChecked; ?>>
                                                <label class="form-check-label" for="kichthuoc_edit_<?php echo $row_size["makt"]; ?>">
                                                  <?php echo $row_size["tenkt"]; ?>
                                                </label>
                                              </div>
                                          <?php
                                            }
                                          }
                                          ?>
                                        </div>
                                      </div>

                                      <!-- Cột 3: Hình ảnh hiện tại -->
                                      <div class="col-md-5">
                                        <div class="mb-3">
                                          <label for="hinhanh_edit_<?php echo $sanPham->getMaSP(); ?>" class="form-label">Hình ảnh:</label>
                                          <input type="file" class="form-control" id="hinhanh_edit_<?php echo $sanPham->getMaSP(); ?>" name="hinhanh_edit" accept="image/*" onchange="previewNewImage_<?php echo $sanPham->getMaSP(); ?>()">
                                        </div>
                                        <!-- Hiển thị hình ảnh mới khi được chọn -->
                                        <div id="newImagePreview_<?php echo $sanPham->getMaSP(); ?>" class="mt-3" style="display: none;">
                                          <p>Xem trước hình ảnh mới:</p>
                                        </div>
                                        <!-- Hiển thị hình ảnh hiện tại -->
                                        <div class="mb-3">
                                          <label for="hinhanh_exxdixlt2" class="form-label">Hình ảnh hiện tại:</label>
                                          <?php
                                          if (!empty($sanPham->getHinhAnh())) {
                                            // Tạo đường dẫn đầy đủ đến hình ảnh hiện tại của sản phẩm
                                            $hinhanh_path = "../../public/assets/images/" . $sanPham->getHinhAnh();

                                            // Hiển thị hình ảnh hiện tại
                                            echo '<br><img src="' . $hinhanh_path . '" alt="previous_image" style="max-width: 100%; height: auto;">';
                                          } else {
                                            echo 'Sản phẩm hiện không có hình ảnh.';
                                          }
                                          ?>
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>

                                <script>
                                  function previewNewImage_<?php echo $sanPham->getMaSP(); ?>() {
                                    const fileInput = document.getElementById('hinhanh_edit_<?php echo $sanPham->getMaSP(); ?>');
                                    const imagePreview = document.getElementById('newImagePreview_<?php echo $sanPham->getMaSP(); ?>');

                                    // Kiểm tra xem người dùng đã chọn tập tin ảnh mới hay chưa
                                    if (fileInput.files && fileInput.files[0]) {
                                      // Hiển thị phần xem trước hình ảnh mới
                                      imagePreview.style.display = 'block';

                                      // Xóa nội dung xem trước cũ
                                      imagePreview.innerHTML = '';

                                      // Thêm dòng văn bản "Xem trước hình ảnh mới" vào phần tử div
                                      const previewText = document.createElement('p');
                                      previewText.textContent = 'Xem trước hình ảnh mới:';
                                      imagePreview.appendChild(previewText);

                                      const reader = new FileReader();

                                      // Đọc dữ liệu hình ảnh dưới dạng URL
                                      reader.onload = function(e) {
                                        // Tạo một phần tử hình ảnh để hiển thị xem trước
                                        const imgElement = document.createElement('img');
                                        imgElement.setAttribute('src', e.target.result);
                                        imgElement.setAttribute('alt', 'new_preview_image');
                                        imgElement.style.maxWidth = '100%';
                                        imgElement.style.height = 'auto';

                                        // Thêm hình ảnh xem trước vào phần tử div
                                        imagePreview.appendChild(imgElement);
                                      }

                                      // Đọc dữ liệu của tập tin hình ảnh mới
                                      reader.readAsDataURL(fileInput.files[0]);
                                    } else {
                                      // Ẩn phần xem trước hình ảnh mới nếu người dùng không chọn tập tin ảnh
                                      imagePreview.style.display = 'none';
                                    }
                                  }
                                </script>

                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    <?php
                    }
                    ?>

                  </div>
                </div>
              <?php
              }
              ?>
            </div>

            <!-- Pagination buttons -->
            <!-- <div class="container mt-4">
              <form method="post" action="SanPham.php"> -->
            <div class="d-flex justify-content-center mt-4 mb-lg-4">
              <ul class="pagination">
                <?php for ($i = 1; $i <= $numberOfPage; $i++) {
                ?>
                  <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="SanPham.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
            <h6 style="font-weight: normal" ;>Không có kết quả tìm kiếm nào.</h6>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>

  <div class="">
    <?php include("footer.php"); ?>
  </div>

  <script>
    // Hàm cập nhật URL khi các thành phần lọc hoặc sắp xếp thay đổi
    function updateUrl() {
      let url = 'SanPham.php';

      // Thu thập các tham số lọc từ các thành phần HTML
      let minPrice = document.getElementById('minPrice').value;
      let maxPrice = document.getElementById('maxPrice').value;
      let category = document.getElementById('maloai_filter').value;
      let sort = document.getElementById('sort').value;

      // Lấy các giá trị của checkbox màu sắc đã được chọn
      let selectedColors = [];
      document.querySelectorAll('.colorCheckbox:checked').forEach(cb => {
        selectedColors.push(cb.value);
      });

      // Lấy các giá trị của checkbox kích thước đã được chọn
      let selectedSizes = [];
      document.querySelectorAll('.sizeCheckbox:checked').forEach(cb => {
        selectedSizes.push(cb.value);
      });

      // Nếu các giá trị lọc có tồn tại và không phải là giá trị mặc định -> Thêm giá trị đó vào đường link
      if (minPrice !== '') {
        url += `?minPrice=${minPrice}`;
      }
      if (maxPrice !== '') {
        url += `${url.includes('?') ? '&' : '?'}maxPrice=${maxPrice}`;
      }
      if (category !== '') {
        url += `${url.includes('?') ? '&' : '?'}loai=${category}`;
      }
      if (selectedColors.length > 0) {
        url += `${url.includes('?') ? '&' : '?'}colorFilter=${selectedColors.join(',')}`;
      }
      if (selectedSizes.length > 0) {
        url += `${url.includes('?') ? '&' : '?'}sizeFilter=${selectedSizes.join(',')}`;
      }
      if (sort !== 'default') {
        url += `${url.includes('?') ? '&' : '?'}sort=${sort}`;
      }

      // Chuyển hướng trình duyệt đến URL mới
      window.location.href = url;
    }

    // Restore lựa chọn của dropdown danh mục sản phẩm từ URL
    function restoreCategorySelection() {
      const urlParams = new URLSearchParams(window.location.search);
      const selectedCategory = urlParams.get('loai'); // Lấy tham số 'loai' từ URL

      if (selectedCategory !== null) {
        const categoryDropdown = document.getElementById('maloai_filter');
        categoryDropdown.value = selectedCategory;
      }
    }

    // Restore giá trị của input minPrice và maxPrice từ URL
    function restorePriceInputs() {
      const urlParams = new URLSearchParams(window.location.search);
      const storedMinPrice = urlParams.get('minPrice');
      const storedMaxPrice = urlParams.get('maxPrice');

      const minPriceInput = document.getElementById('minPrice');
      const maxPriceInput = document.getElementById('maxPrice');

      if (storedMinPrice !== null) {
        minPriceInput.value = storedMinPrice;
      }

      if (storedMaxPrice !== null) {
        maxPriceInput.value = storedMaxPrice;
      }
    }

    // Restore checkbox màu sắc và kích thước từ URL
    function restoreCheckboxState() {
      const urlParams = new URLSearchParams(window.location.search);

      // Màu sắc
      const colorCheckboxes = document.querySelectorAll('.colorCheckbox');
      colorCheckboxes.forEach(cb => {
        const colorId = cb.value;
        if (urlParams.has('colorFilter')) {
          const selectedColors = urlParams.get('colorFilter').split(',');
          if (selectedColors.includes(colorId)) {
            cb.checked = true;
          }
        }
      });

      // Kích thước
      const sizeCheckboxes = document.querySelectorAll('.sizeCheckbox');
      sizeCheckboxes.forEach(cb => {
        const sizeId = cb.value;
        if (urlParams.has('sizeFilter')) {
          const selectedSizes = urlParams.get('sizeFilter').split(',');
          if (selectedSizes.includes(sizeId)) {
            cb.checked = true;
          }
        }
      });
    }

    // Listen event khi trang được tải lại
    document.addEventListener('DOMContentLoaded', function() {

      // Gọi các hàm restore
      restoreCategorySelection();
      restorePriceInputs();
      restoreCheckboxState();

      // Listen event khi các tuỳ chọn lọc và sắp xếp thay đổi
      document.getElementById('minPrice').addEventListener('change', updateUrl);
      document.getElementById('maxPrice').addEventListener('change', updateUrl);
      document.getElementById('maloai_filter').addEventListener('change', updateUrl);
      document.getElementById('sort').addEventListener('change', updateUrl);

      // Listen event khi các checkbox màu sắc và kích thước được chọn
      const colorCheckboxes = document.querySelectorAll('.colorCheckbox');
      colorCheckboxes.forEach(cb => {
        cb.addEventListener('click', updateUrl);
      });

      const sizeCheckboxes = document.querySelectorAll('.sizeCheckbox');
      sizeCheckboxes.forEach(cb => {
        cb.addEventListener('click', updateUrl);
      });
    });
  </script>

</body>

</html>