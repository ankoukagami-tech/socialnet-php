<?php declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function db(): PDO
{
  static $pdo = null;
  if ($pdo instanceof PDO) {
    return $pdo;
  }

  $config = require __DIR__ . '/config.load.php';
  $db = $config['db'];

  $dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    (string)$db['host'],
    (int)$db['port'],
    (string)$db['name'],
    (string)$db['charset']
  );

  $pdo = new PDO($dsn, (string)$db['user'], (string)$db['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);

  return $pdo;
}

