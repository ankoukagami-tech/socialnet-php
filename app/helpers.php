<?php declare(strict_types=1);

function h(string $s): string
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function redirect_to(string $path): void
{
  header('Location: ' . $path);
  exit;
}
