<?php declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$pdo = db();

$owner = trim((string)($_GET['owner'] ?? ''));
if ($owner === '') {
  $owner = (string)current_username();
}

$stmt = $pdo->prepare('SELECT username, fullname, description FROM account WHERE username = ? LIMIT 1');
$stmt->execute([$owner]);
$acc = $stmt->fetch();

if (!$acc) {
  http_response_code(404);
  $acc = ['username' => $owner, 'fullname' => '', 'description' => null];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile</title>
</head>
<body>
  <?php require __DIR__ . '/../includes/menubar.php'; ?>

  <h1>Profile</h1>
  <p>Owner: <strong><?= h((string)$acc['username']) ?></strong></p>
  <?php if ((string)$acc['fullname'] !== ''): ?>
    <p>Fullname: <strong><?= h((string)$acc['fullname']) ?></strong></p>
  <?php endif; ?>

  <h2>Profile Page content</h2>
  <div style="white-space: pre-wrap; border: 1px solid #ddd; padding: 12px;">
    <?= h((string)($acc['description'] ?? '')) ?>
  </div>

  <?php if (http_response_code() === 404): ?>
    <p style="color:red;">User not found.</p>
  <?php endif; ?>
</body>
</html>

