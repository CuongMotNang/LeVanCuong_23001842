<?php
require_once 'common/auth.php';
requireLogin();
require_once 'model/product.php';

$pageTitle = 'Danh sách sản phẩm';
$products = getAllProducts();

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

include 'view/header.php';
?>
<h2>Danh sách sản phẩm</h2>

<?php if ($message): ?>
    <p class="success"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<p><a href="product_add.php">[+ Thêm sản phẩm]</a></p>

<table>
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Chức năng</th>
    </tr>
    <?php if (empty($products)): ?>
        <tr><td colspan="5">Chưa có sản phẩm nào.</td></tr>
    <?php else: ?>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
            <td><?= $p['quantity'] ?></td>
            <td>
                <a href="product_edit.php?id=<?= $p['id'] ?>">[Sửa]</a>
                <a href="product_delete.php?id=<?= $p['id'] ?>">[Xóa]</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<?php include 'view/footer.php'; ?>
