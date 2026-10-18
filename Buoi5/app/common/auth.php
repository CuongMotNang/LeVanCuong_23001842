<?php
// Khởi động session và các hàm hỗ trợ kiểm soát truy cập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Gọi ở đầu các trang cần đăng nhập
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
