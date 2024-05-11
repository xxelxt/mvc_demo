<?php

    $originalFilePath = 'data/products.json';
    $editedFilePath = 'data/products_edit.json';

    $jsonData = file_get_contents($originalFilePath);

    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['products'])) {
        die('Dữ liệu JSON không hợp lệ');
    }

    foreach ($data['products'] as &$product) {
        $product['quantity'] = rand(1, 100);
        $product['price'] = rand(100000, 1000000);
    }

    $editedData = json_encode($data, JSON_PRETTY_PRINT);

    file_put_contents($editedFilePath, $editedData);

    echo "Đã tạo thành công file products_edit.json.";
