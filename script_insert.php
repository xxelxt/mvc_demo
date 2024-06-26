<?php

    include 'config/database.php';
    require_once 'app/models/M_SanPham.php';
    require_once 'app/models/M_KichThuoc.php';
    require_once 'app/models/M_MauSac.php';

    global $conn;

    $jsonData = file_get_contents('data/products.json');
    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['products'])) {
        die('Dữ liệu JSON không hợp lệ');
    }

    $sanPhamModel = new SanPhamModel($conn);
    $kichThuocModel = new KichThuocModel($conn);
    $mauSacModel = new MauSacModel($conn);

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

        $sanPhamModel -> themSanPham($sanPham, $colors, $sizes);
    }

    echo "Đã chèn dữ liệu vào CSDL. ";
