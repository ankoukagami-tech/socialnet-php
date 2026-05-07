<?php declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

$error = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify_or_die($_POST['csrf_token'] ?? null);

  $username = trim((string)($_POST['username'] ?? ''));
  $fullname = trim((string)($_POST['fullname'] ?? ''));
  $password = (string)($_POST['password'] ?? '');
  $description = trim((string)($_POST['description'] ?? ''));

  if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
    $error = 'Invalid username (3-50 chars: letters/numbers/_).';
  } elseif ($fullname === '' || mb_strlen($fullname) > 100) {
    $error = 'Invalid fullname.';
  } elseif (mb_strlen($password) < 6) {
    $error = 'Password must be at least 6 characters.';
  } elseif (mb_strlen($description) > 2000) {
    $error = 'Description is too long.';
  } else {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id FROM account WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
      $error = 'Username already exists.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $ins = $pdo->prepare('INSERT INTO account (username, fullname, password_hash, description) VALUES (?, ?, ?, ?)');
      $ins->execute([$username, $fullname, $hash, $description !== '' ? $description : null]);
      $ok = 'User created. You can now sign in at /socialnet/signin.php';
      $_POST = [];
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - New User</title>
</head>
<body>
  <h1>Admin: Create New User</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?= h($error) ?></p>
  <?php endif; ?>
  <?php if ($ok): ?>
    <p style="color:green;"><?= h($ok) ?></p>
  <?php endif; ?>

  <form method="post" action="/admin/newuser.php">
    <?= csrf_hidden_field(); ?>
    <div>
      <label>username</label><br>
      <input name="username" required minlength="3" maxlength="50" value="<?= h((string)($_POST['username'] ?? '')) ?>">
    </div>
    <div>
      <label>fullname</label><br>
      <input name="fullname" required maxlength="100" value="<?= h((string)($_POST['fullname'] ?? '')) ?>">
    </div>
    <div>
      <label>password</label><br>
      <input name="password" type="password" required minlength="6">
    </div>
    <div>
      <label>description</label><br>
      <textarea name="description" rows="4" maxlength="2000"><?= h((string)($_POST['description'] ?? '')) ?></textarea>
    </div>
    <button type="submit">Create user</button>
  </form>
</body>
</html>

