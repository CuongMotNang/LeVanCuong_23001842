<?php
require_once __DIR__ . '/../common/dbConnect.php';

// Lấy tất cả sản phẩm
function getAllProducts() {
    global $conn;
    $products = [];
    $result = $conn->query('SELECT * FROM products ORDER BY id ASC');
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Lấy 1 sản phẩm theo id (trả về null nếu không có)
function getProductById($id) {
    global $conn;
    $stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

// Thêm sản phẩm
function addProduct($name, $price, $quantity) {
    global $conn;
    $stmt = $conn->prepare('INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)');
    $stmt->bind_param('sdi', $name, $price, $quantity);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// Cập nhật sản phẩm
function updateProduct($id, $name, $price, $quantity) {
    global $conn;
    $stmt = $conn->prepare('UPDATE products SET name = ?, price = ?, quantity = ? WHERE id = ?');
    $stmt->bind_param('sdii', $name, $price, $quantity, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// Xóa sản phẩm
function deleteProduct($id) {
    global $conn;
    $stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
    $stmt->bind_param('i', $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// Kiểm tra dữ liệu đầu vào dùng chung cho thêm/sửa. Trả về mảng lỗi.
function validateProduct($name, $price, $quantity) {
    $errors = [];
    if (trim($name) === '') {
        $errors[] = 'Tên sản phẩm không được để trống.';
    } elseif (mb_strlen(trim($name)) > 100) {
        $errors[] = 'Tên sản phẩm không được quá 100 ký tự.';
    }
    if (!is_numeric($price) || (float)$price <= 0) {
        $errors[] = 'Giá phải là số lớn hơn 0.';
    }
    if (filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) {
        $errors[] = 'Số lượng phải là số nguyên lớn hơn hoặc bằng 0.';
    }
    return $errors;
}
