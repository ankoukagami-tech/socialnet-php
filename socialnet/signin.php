<?php declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

if (is_logged_in()) {
  redirect_to('/socialnet/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify_or_die($_POST['csrf_token'] ?? null);

  $username = trim((string)($_POST['username'] ?? ''));
  $password = (string)($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error = 'Please enter username and password.';
  } else {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM account WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $acc = $stmt->fetch();

    if (!$acc || !password_verify($password, (string)$acc['password_hash'])) {
      $error = 'Invalid username or password.';
    } else {
      login_user((int)$acc['id'], (string)$acc['username']);
      redirect_to('/socialnet/index.php');
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign In</title>
</head>
<body>
  <h1>Sign In</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?= h($error) ?></p>
  <?php endif; ?>

  <form method="post" action="/socialnet/signin.php">
    <?= csrf_hidden_field(); ?>
    <div>
      <label>username</label><br>
      <input name="username" required value="<?= h((string)($_POST['username'] ?? '')) ?>">
    </div>
    <div>
      <label>password</label><br>
      <input name="password" type="password" required>
    </div>
    <button type="submit">Sign In</button>
  </form>

  <p>Admin creates accounts at <code>/admin/newuser.php</code></p>
</body>
</html>

