<?php declare(strict_types=1);

$defaults = require __DIR__ . '/config.defaults.php';
$localPath = __DIR__ . '/config.local.php';

if (is_file($localPath)) {
  $local = require $localPath;
  if (!is_array($local)) {
    throw new RuntimeException('config.local.php must return an array');
  }
  return array_replace_recursive($defaults, $local);
}

return $defaults;

