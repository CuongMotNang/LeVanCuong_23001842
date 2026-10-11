<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Quản lý sản phẩm' ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Quản lý giỏ hàng</h1>
    <p>
        <a href="product_list.php">Danh sách sản phẩm</a> |
        <a href="product_add.php">Thêm sản phẩm</a>
    </p>
    <hr>
