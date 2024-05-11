<?php

include 'config/database.php';
require_once 'app/models/M_SanPham.php';
require_once 'app/models/M_KichThuoc.php';
require_once 'app/models/M_MauSac.php';

$totalProducts = 1000;

$products = [];

$mauSacModel = new MauSacModel($conn);
$kichThuocModel = new KichThuocModel($conn);

for ($i = 1; $i <= $totalProducts; $i++) {
  $productId = "SP" . str_pad($i, 3, '0', STR_PAD_LEFT);
  $productName = "Product " . $i;
  $price = rand(100000, 1000000);
  $quantity = rand(1, 100);
  $image = "default.jpg";
  $categoryId = "L" . str_pad(rand(1, 7), 2, '0', STR_PAD_LEFT);

  $colors = $mauSacModel->layDanhSachMauSac();
  $colorNames = array_map(function ($color) {
    return $color->getTenMau();
  }, $colors);

  $sizes = $kichThuocModel->layDanhSachKT();
  $sizeNames = array_map(function ($size) {
    return $size->getTenKT();
  }, $sizes);

  $randomColorIndexes = array_rand($colorNames, rand(1, count($colorNames))); // Chọn ngẫu nhiên màu sắc
  $randomSizeIndexes = array_rand($sizeNames, rand(1, count($sizeNames))); // Chọn ngẫu nhiên kích thước

  $selectedColors = [];
  $selectedSizes = [];

  if (!is_array($randomColorIndexes)) {
    $randomColorIndexes = [$randomColorIndexes];
  }

  foreach ($randomColorIndexes as $colorIndex) {
    $selectedColors[] = $colorNames[$colorIndex];
  }

  if (!is_array($randomSizeIndexes)) {
    $randomSizeIndexes = [$randomSizeIndexes];
  }
  foreach ($randomSizeIndexes as $sizeIndex) {
    $selectedSizes[] = $sizeNames[$sizeIndex];
  }

  $product = [
    "product_id" => $productId,
    "product_name" => $productName,
    "price" => $price,
    "quantity" => $quantity,
    "image" => $image,
    "category_id" => $categoryId,
    "colors" => $selectedColors,
    "sizes" => $selectedSizes
  ];

  $products[] = $product;
}

$jsonData = json_encode(["products" => $products], JSON_PRETTY_PRINT);

$filename = 'data/products.json';
file_put_contents($filename, $jsonData);

echo "Đã tạo thành công tệp products.json có 1000 sản phẩm.";
