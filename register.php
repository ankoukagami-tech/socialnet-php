<?php declare(strict_types=1);

require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify_or_die($_POST['csrf_token'] ?? null);

  $username = trim((string)($_POST['username'] ?? ''));
  $email = trim((string)($_POST['email'] ?? ''));
  $password = (string)($_POST['password'] ?? '');
  $description = trim((string)($_POST['description'] ?? ''));

  if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    $error = 'Username không hợp lệ (3-50 ký tự, chữ cái/số/_).';
  } elseif ($password === '' || mb_strlen($password) < 8) {
    $error = 'Mật khẩu phải từ 8 ký tự trở lên.';
  } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Email không hợp lệ.';
  } elseif (mb_strlen($description) > 2000) {
    $error = 'Description quá dài.';
  } else {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
      $error = 'Username đã tồn tại.';
    } else {
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, description) VALUES (?, ?, ?, ?)');
      $stmt->execute([$username, $email !== '' ? $email : null, $passwordHash, $description !== '' ? $description : null]);

      redirect_to('/login.php?registered=1');
    }
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng ký</title>
</head>
<body>
  <h1>Đăng ký</h1>

  <?php if ($error): ?>
    <p style="color: red;"><?= h($error) ?></p>
  <?php endif; ?>

  <form method="post" action="/register.php">
    <?= csrf_hidden_field(); ?>
    <div>
      <label>Username</label>
      <input name="username" required minlength="3" maxlength="50" value="<?= h((string)($_POST['username'] ?? '')) ?>">
    </div>
    <div>
      <label>Email (tuỳ chọn)</label>
      <input name="email" type="email" maxlength="100" value="<?= h((string)($_POST['email'] ?? '')) ?>">
    </div>
    <div>
      <label>Password</label>
      <input name="password" type="password" required minlength="8">
    </div>
    <div>
      <label>Description</label>
      <textarea name="description" rows="4" maxlength="2000"><?= h((string)($_POST['description'] ?? '')) ?></textarea>
    </div>
    <button type="submit">Tạo tài khoản</button>
  </form>

  <p><a href="/login.php">Đã có tài khoản? Đăng nhập</a></p>
</body>
</html>

