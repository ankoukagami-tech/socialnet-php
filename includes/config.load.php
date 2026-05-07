<?php declare(strict_types=1);

$base = require __DIR__ . '/config.php';
$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
  $override = require $local;
  if (is_array($override)) {
    $base = array_replace_recursive($base, $override);
  }
}
return $base;

