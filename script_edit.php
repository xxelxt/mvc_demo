<?php

    include 'config/database.php';
    require_once 'app/models/M_SanPham.php';
    require_once 'app/models/M_KichThuoc.php';
    require_once 'app/models/M_MauSac.php';
    require_once 'app/models/redisCache.php';

    global $conn;

    $startTime = microtime(true);

    $jsonData = file_get_contents('data/products_edit.json');
    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['products'])) {
        die('Dữ liệu JSON không hợp lệ');
    }

    $sanPhamModel = new SanPhamModel($conn);
    $kichThuocModel = new KichThuocModel($conn);
    $mauSacModel = new MauSacModel($conn);
    $cache = new redisCache();

    foreach ($data['products'] as $product) {
        $productId = $product['product_id'];
        $productName = $product['product_name'];
        $price = $product['price'];

        $quantity = $product['quantity'];
        $image = $product['image'];
        $categoryId = $product['category_id'];

        $colors = $product['colors'];
        $sizes = $product['sizes'];

        $sanPham = new SanPham($productId, $productName, $price, $quantity, $image, $categoryId);
        $existingProduct = $cache->get('sanpham_' . $productId);

        if ($sanPhamModel->capNhatSanPham($sanPham, $colors, $sizes)) {
            $product = [
                'masp' => $productId,
                'tensp' => $productName,
                'gia' => $price,
                'soluong' => $quantity,
                'hinhanh' => $image,
                'maloai' => $categoryId,
                'mausac' => $colors,
                'kichthuoc' => $sizes
            ];
            $cache->set('sanpham_' . $productId, $product);
        }
    }

    $endTime = microtime(true);

    $executionTime = $endTime - $startTime;

    echo "Đã chèn dữ liệu vào CSDL. ";

    echo "Thời gian chạy: " . number_format($executionTime) . " giây";
