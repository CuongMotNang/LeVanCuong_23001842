<?php
require_once 'common/auth.php';
require_once 'model/user.php';

// Đã đăng nhập thì vào thẳng trang quản lý
if (isLoggedIn()) {
    header('Location: product_list.php');
    exit;
}

$pageTitle = 'Đăng nhập';
$error = '';
$username = '';

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập tên đăng nhập và mật khẩu.';
    } else {
        $user = getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: product_list.php');
            exit;
        }
        $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
    }
}

include 'view/header.php';
?>
<h2>Đăng nhập</h2>

<?php if ($message): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="post" action="login.php">
    <label>Tên đăng nhập:
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">
    </label>
    <label>Mật khẩu:
        <input type="password" name="password">
    </label>
    <button type="submit">Đăng nhập</button>
</form>
<p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
<?php include 'view/footer.php'; ?>
