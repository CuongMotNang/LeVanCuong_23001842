<?php
require_once 'common/auth.php';
requireLogin();
require_once 'model/product.php';

$pageTitle = 'Xóa sản phẩm';
$id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT);
$product = $id ? getProductById($id) : null;

// Sản phẩm không tồn tại
if (!$product) {
    include 'view/header.php';
    echo '<p class="error">Sản phẩm không tồn tại hoặc đã bị xóa.</p>';
    echo '<p><a href="product_list.php">Quay lại danh sách</a></p>';
    include 'view/footer.php';
    exit;
}

// Người dùng đã xác nhận -> xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    if (deleteProduct($id)) {
        $_SESSION['message'] = 'Đã xóa sản phẩm "' . $product['name'] . '".';
    } else {
        $_SESSION['message'] = 'Xóa sản phẩm thất bại.';
    }
    header('Location: product_list.php');
    exit;
}

// Hiển thị trang xác nhận
include 'view/header.php';
?>
<h2>Xác nhận xóa sản phẩm</h2>
<p>Bạn có chắc chắn muốn xóa sản phẩm <strong><?= htmlspecialchars($product['name']) ?></strong> (ID: <?= $id ?>) không?</p>

<form method="post" action="product_delete.php?id=<?= $id ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <button type="submit" name="confirm" value="1">Xác nhận xóa</button>
    <a href="product_list.php">Hủy</a>
</form>
<?php include 'view/footer.php'; ?>
