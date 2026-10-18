<?php
// Kết nối MySQL - các file khác dùng require_once để tái sử dụng $conn
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'shopping_cart';

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_errno) {
    die('Kết nối cơ sở dữ liệu thất bại: ' . htmlspecialchars($conn->connect_error));
}
$conn->set_charset('utf8mb4');
