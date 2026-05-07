<?php declare(strict_types=1);

require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/db.php';

$error = '';
if (isset($_GET['registered']) && $_GET['registered'] === '1') {
  $error = 'Đăng ký thành công. Vui lòng đăng nhập.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify_or_die($_POST['csrf_token'] ?? null);

  $username = trim((string)($_POST['username'] ?? ''));
  $password = (string)($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error = 'Vui lòng nhập đầy đủ username và password.';
  } else {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string)$user['password_hash'])) {
      $error = 'Sai username hoặc password.';
    } else {
      // Prevent session fixation.
      session_regenerate_id(true);
      $_SESSION['user_id'] = (int)$user['id'];
      $_SESSION['username'] = (string)$user['username'];
      redirect_to('/description.php');
    }
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập</title>
</head>
<body>
  <h1>Đăng nhập</h1>

  <?php if ($error): ?>
    <p style="color: red;"><?= h($error) ?></p>
  <?php endif; ?>

  <form method="post" action="/login.php">
    <?= csrf_hidden_field(); ?>
    <div>
      <label>Username</label>
      <input name="username" required value="<?= h((string)($_POST['username'] ?? '')) ?>">
    </div>
    <div>
      <label>Password</label>
      <input name="password" type="password" required>
    </div>
    <button type="submit">Login</button>
  </form>

  <p><a href="/register.php">Chưa có tài khoản? Đăng ký</a></p>
</body>
</html>

