<?php declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$pdo = db();
$meId = current_account_id();

$stmt = $pdo->prepare('SELECT username, fullname FROM account WHERE id = ? LIMIT 1');
$stmt->execute([$meId]);
$me = $stmt->fetch();
if (!$me) {
  logout_user();
  redirect_to('/socialnet/signin.php');
}

$others = $pdo->query('SELECT username, fullname FROM account ORDER BY username ASC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SocialNet - Home</title>
</head>
<body>
  <?php require __DIR__ . '/../includes/menubar.php'; ?>

  <h1>Home</h1>

  <h2>Your info</h2>
  <ul>
    <li>username: <strong><?= h((string)$me['username']) ?></strong></li>
    <li>fullname: <strong><?= h((string)$me['fullname']) ?></strong></li>
  </ul>

  <h2>Other users</h2>
  <ul>
    <?php foreach ($others as $u): ?>
      <li>
        <a href="/socialnet/profile.php?owner=<?= urlencode((string)$u['username']) ?>">
          <?= h((string)$u['username']) ?> (<?= h((string)$u['fullname']) ?>)
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>

