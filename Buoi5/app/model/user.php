<?php
require_once __DIR__ . '/../common/dbConnect.php';

// Lấy user theo username (null nếu không có)
function getUserByUsername($username) {
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

// Thêm user mới, mật khẩu được băm bằng password_hash()
function addUser($username, $password) {
    global $conn;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->bind_param('ss', $username, $hash);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}
