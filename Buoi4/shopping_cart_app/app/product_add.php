<?php
session_start();
require_once 'model/product.php';

$pageTitle = 'Thêm sản phẩm';
$errors = [];
$name = $price = $quantity = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $price    = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    $errors = validateProduct($name, $price, $quantity);

    if (empty($errors)) {
        if (addProduct($name, (float)$price, (int)$quantity)) {
            $_SESSION['message'] = 'Thêm sản phẩm thành công!';
            header('Location: product_list.php');
            exit;
        }
        $errors[] = 'Có lỗi khi thêm sản phẩm, vui lòng thử lại.';
    }
}

include 'view/header.php';
?>
<h2>Thêm sản phẩm</h2>

<?php foreach ($errors as $e): ?>
    <p class="error">- <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" action="product_add.php">
    <label>Tên sản phẩm:
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    </label>
    <label>Giá:
        <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>">
    </label>
    <label>Số lượng:
        <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>">
    </label>
    <button type="submit">Thêm</button>
    <a href="product_list.php">Quay lại</a>
</form>
<?php include 'view/footer.php'; ?>
