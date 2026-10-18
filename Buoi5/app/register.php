<?php
require_once 'common/auth.php';
require_once 'model/user.php';

if (isLoggedIn()) {
    header('Location: product_list.php');
    exit;
}

$pageTitle = 'Đăng ký';
$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors[] = 'Tên đăng nhập không được để trống.';
    } elseif (mb_strlen($username) > 50) {
        $errors[] = 'Tên đăng nhập không được quá 50 ký tự.';
    }
    if ($password === '') {
        $errors[] = 'Mật khẩu không được để trống.';
    }

    // Kiểm tra tên đăng nhập đã tồn tại
    if (empty($errors) && getUserByUsername($username)) {
        $errors[] = 'Tên đăng nhập đã tồn tại, vui lòng chọn tên khác.';
    }

    if (empty($errors)) {
        if (addUser($username, $password)) {
            $_SESSION['message'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            header('Location: login.php');
            exit;
        }
        $errors[] = 'Đăng ký thất bại, vui lòng thử lại.';
    }
}

include 'view/header.php';
?>
<h2>Đăng ký tài khoản</h2>

<?php foreach ($errors as $e): ?>
    <p class="error">- <?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" action="register.php">
    <label>Tên đăng nhập:
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">
    </label>
    <label>Mật khẩu:
        <input type="password" name="password">
    </label>
    <button type="submit">Đăng ký</button>
</form>
<p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
<?php include 'view/footer.php'; ?>
