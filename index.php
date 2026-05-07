<?php declare(strict_types=1);

require __DIR__ . '/app/auth.php';

$user = current_username();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>php-auth-app</title>
</head>
<body>
  <h1>php-auth-app</h1>

  <?php if ($user): ?>
    <p>Xin chào, <?= h($user) ?></p>
    <p><a href="/description.php">Xem/Cập nhật description</a></p>
    <p><a href="/logout.php">Logout</a></p>
  <?php else: ?>
    <p><a href="/register.php">Đăng ký</a></p>
    <p><a href="/login.php">Đăng nhập</a></p>
  <?php endif; ?>
</body>
</html>

