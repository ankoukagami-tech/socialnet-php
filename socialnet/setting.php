<?php declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

require_login();

$pdo = db();
$meId = current_account_id();

$stmt = $pdo->prepare('SELECT description FROM account WHERE id = ? LIMIT 1');
$stmt->execute([$meId]);
$me = $stmt->fetch();
if (!$me) {
  logout_user();
  redirect_to('/socialnet/signin.php');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify_or_die($_POST['csrf_token'] ?? null);
  $desc = trim((string)($_POST['description'] ?? ''));
  if (mb_strlen($desc) > 2000) {
    $message = 'Description too long.';
  } else {
    $upd = $pdo->prepare('UPDATE account SET description = ? WHERE id = ?');
    $upd->execute([$desc !== '' ? $desc : null, $meId]);
    $message = 'Saved.';
    $stmt->execute([$meId]);
    $me = $stmt->fetch();
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Setting</title>
</head>
<body>
  <?php require __DIR__ . '/../includes/menubar.php'; ?>

  <h1>Setting</h1>

  <?php if ($message): ?>
    <p style="<?= $message === 'Saved.' ? 'color:green;' : 'color:red;' ?>"><?= h($message) ?></p>
  <?php endif; ?>

  <form method="post" action="/socialnet/setting.php">
    <?= csrf_hidden_field(); ?>
    <div>
      <label>Profile Page content (description)</label><br>
      <textarea name="description" rows="8" maxlength="2000"><?= h((string)($me['description'] ?? '')) ?></textarea>
    </div>
    <button type="submit">Save</button>
  </form>
</body>
</html>

