<?php
session_start();
require_once 'model/product.php';

$pageTitle = 'Sửa sản phẩm';
$id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT);
$product = $id ? getProductById($id) : null;
$errors = [];

if (!$product) {
    include 'view/header.php';
    echo '<p class="error">Sản phẩm không tồn tại.</p>';
    echo '<p><a href="product_list.php">Quay lại danh sách</a></p>';
    include 'view/footer.php';
    exit;
}

$name     = $product['name'];
$price    = $product['price'];
$quantity = $product['quantity'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $price    = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    $errors = validateProduct($name, $price, $quantity);

    if (empty($errors)) {
        if (updateProduct($id, $name, (float)$price, (int)$quantity)) {
            $_SESSION['message'] = 'Cập nhật sản phẩm thành công!';
            header('Location: product_list.php');
            exit;
        }
        $errors[] = 'Có lỗi khi cập nhật sản phẩm, vui lòng thử lại.';
    }
}

include 'view/header.php';
?>
<h2>Sửa sản phẩm (ID: <?= $id ?>)</h2>

<?php foreach ($errors as $e): ?>
    <p class="error">- <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" action="product_edit.php?id=<?= $id ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Tên sản phẩm:
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
    </label>
    <label>Giá:
        <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>">
    </label>
    <label>Số lượng:
        <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>">
    </label>
    <button type="submit">Cập nhật</button>
    <a href="product_list.php">Quay lại</a>
</form>
<?php include 'view/footer.php'; ?>
