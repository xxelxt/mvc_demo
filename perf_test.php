<?php
    include 'config/database.php';
    require_once 'app/models/M_SanPham.php';
    require_once 'app/models/redisCache.php';

    global $conn;

    $sanPhamModel = new SanPhamModel($conn);

    $startTime = microtime(true);

    $recordPerPage = 500;

    for ($i = 1; $i <= 1000; $i++) {
        $offset = ($i - 1) * $recordPerPage;

        list($dsSanPham, $totalRecords) = $sanPhamModel -> layDanhSach($recordPerPage, $offset);
    }

    $endTime = microtime(true);

    $executionTime = $endTime - $startTime;

    echo "Thời gian chạy 1000 lần lặp là " . $executionTime . " giây.";
