<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'admin');
    define('DB_NAME', 'hehe');
    define('DB_PORT', 3306);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

    if ($conn -> connect_error) {
        die("Lỗi kết nối: " . $conn -> connect_error);
    }