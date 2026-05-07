<?php declare(strict_types=1);

require __DIR__ . '/app/auth.php';
require __DIR__ . '/app/db.php';

require_login();

$userId = current_user_id();
if ($userId === null) {
  redirect_to('/login.php');
}

$pdo = db();

// Load user data
$stmt = $pdo->prepare('SELECT username, description FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$userId]);
$row = $stmt->fetch();

if (!$row) {
  // User might have been deleted.
  session_destroy();
  redirect_to('/login.php');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify_or_die($_POST['csrf_token'] ?? null);
  $description = trim((string)($_POST['description'] ?? ''));

  if (mb_strlen($description) > 2000) {
    $message = 'Description quá dài.';
  } else {
    $upd = $pdo->prepare('UPDATE users SET description = ? WHERE id = ?');
    $upd->execute([$description !== '' ? $description : null, $userId]);
    $message = 'Đã lưu description.';
    // Reload
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Description</title>
</head>
<body>
  <h1>Description</h1>

  <p>Xin chào, <?= h((string)$row['username']) ?></p>
  <p><a href="/logout.php">Logout</a></p>

  <?php if ($message): ?>
    <p style="<?= $message === 'Đã lưu description.' ? 'color: green;' : 'color: red;' ?>"><?= h($message) ?></p>
  <?php endif; ?>

  <form method="post" action="/description.php">
    <?= csrf_hidden_field(); ?>
    <div>
      <label>Description</label>
      <textarea name="description" rows="6" maxlength="2000"><?= h((string)($row['description'] ?? '')) ?></textarea>
    </div>
    <button type="submit">Lưu</button>
  </form>
</body>
</html>

