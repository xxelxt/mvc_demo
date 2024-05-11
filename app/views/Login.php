<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng nhập</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .card-body,
            .card-header {
                padding: 20px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row justify-content-center align-items-center vh-100">
                <div class="col-md-4">
                    <div class="card">
                        <h3 class="card-header text-center">Đăng nhập</h3>
                        <div class="card-body">
                            <?php
                                if (isset($_GET['error']) && $_GET['error'] == 1) {
                                    echo "<div class='alert alert-danger' role='alert'>Tên đăng nhập hoặc mật khẩu không đúng.</div>";
                                }
                            ?>
                            <?php
                                if (isset($_GET['logged_out']) && $_GET['logged_out'] == 1) {
                                    echo "<div class='alert alert-info' role='alert'>Đăng xuất thành công.</div>";
                                }
                            ?>
                            <form method="POST" action="../controllers/C_AuthProcess.php?action=login">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Tên đăng nhập</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
