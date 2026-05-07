<?php declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();

$config = require __DIR__ . '/../includes/config.load.php';
$studentName = (string)($config['student']['name'] ?? 'YOUR_NAME');
$studentNumber = (string)($config['student']['number'] ?? 'YOUR_STUDENT_NUMBER');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About</title>
</head>
<body>
  <?php require __DIR__ . '/../includes/menubar.php'; ?>

  <h1>About</h1>
  <p>Student name: <strong><?= h($studentName) ?></strong></p>
  <p>Student number: <strong><?= h($studentNumber) ?></strong></p>
</body>
</html>

